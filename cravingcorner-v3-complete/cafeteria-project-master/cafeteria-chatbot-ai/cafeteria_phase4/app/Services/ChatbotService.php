<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ChatbotService
{
    public function __construct(private RecommendationService $recommendations) {}

    /**
     * Tables/columns the ADMIN chatbot may ever read. Never includes
     * password/remember_token, no matter what the model returns.
     */
    private const ADMIN_TABLES = [
        'users' => ['id', 'name', 'email', 'role', 'created_at'],
        'categories' => ['id', 'name', 'type', 'description'],
        'food_items' => ['id', 'category_id', 'name', 'price', 'available_quantity', 'status', 'created_at'],
        'beverages' => ['id', 'category_id', 'name', 'price', 'available_quantity', 'status', 'created_at'],
        'orders' => ['id', 'user_id', 'total_price', 'status', 'payment_status', 'created_at'],
        'order_items' => ['id', 'order_id', 'itemable_id', 'itemable_type', 'quantity', 'price', 'subtotal'],
    ];

    public function respond(User $user, string $message): string
    {
        // Authorization branch happens here, BEFORE anything reaches the AI.
        // A customer can never trigger the admin (SQL) path, and vice versa.
        return $user->isAdmin()
            ? $this->respondAsAdmin($message)
            : $this->respondAsCustomer($user, $message);
    }

    // ---- ADMIN: role-scoped, validated read-only SQL -----------------

    private function respondAsAdmin(string $message): string
    {
        $context = $this->answerFromDatabase($message);

        return $this->ask(
            system: "You are the cafeteria's admin assistant. Answer using ONLY the database context given. Never invent numbers. If the context says no query was needed, answer conversationally.\n\nDatabase context:\n".($context ?? 'No database query was needed for this request.'),
            user: $message,
        );
    }

    private function answerFromDatabase(string $message): ?string
    {
        $schema = json_encode(self::ADMIN_TABLES, JSON_THROW_ON_ERROR);

        $decision = json_decode($this->ask(
            system: 'You translate cafeteria admin questions into read-only SQL. Reply with ONLY a JSON object: {"needs_database": boolean, "sql": string}. SQL must be a single SELECT using only the given tables/columns, no comments, no semicolons.',
            user: "Schema: {$schema}\nQuestion: {$message}",
            jsonMode: true,
        ), true);

        if (! is_array($decision) || ! ($decision['needs_database'] ?? false)) {
            return null;
        }

        $sql = $decision['sql'] ?? null;
        if (! is_string($sql) || $sql === '') {
            return null;
        }

        $this->validateSql($sql, self::ADMIN_TABLES);

        return json_encode(DB::select($sql.' limit 100'), JSON_THROW_ON_ERROR);
    }

    // ---- CUSTOMER: no SQL access, only pre-computed recommendations ---

    private function respondAsCustomer(User $user, string $message): string
    {
        // The AI never touches the database for a customer. We compute
        // the allowed data ourselves and hand it over as fixed context.
        $topMatches = $this->recommendations->scoreItemsFor($user)
            ->take(8)
            ->map(fn ($row) => [
                'name' => $row['item']->name,
                'type' => $row['type'],
                'price' => (float) $row['item']->price,
                'match_percentage' => $row['match'],
            ]);

        $context = json_encode($topMatches->values(), JSON_THROW_ON_ERROR);

        return $this->ask(
            system: "You are the cafeteria's customer assistant for {$user->name}. Recommend ONLY from the items listed below — never mention or invent any other item. Mention the match percentage naturally when relevant.\n\nAvailable matches:\n{$context}",
            user: $message,
        );
    }

    // ---- shared plumbing -----------------------------------------------

    private function validateSql(string $sql, array $allowedTables): void
    {
        $normalized = strtolower(trim($sql));

        if (! Str::startsWith($normalized, 'select ') || str_contains($normalized, ';') || str_contains($normalized, '--') || str_contains($normalized, '/*')) {
            throw new RuntimeException('Only read-only SELECT queries are allowed.');
        }

        if (preg_match('/\b(insert|update|delete|drop|alter|create|replace|union|into\s+outfile)\b/i', $normalized)) {
            throw new RuntimeException('Only read-only SELECT queries are allowed.');
        }

        if (preg_match('/\b(password|remember_token|token|secret)\b/i', $normalized)) {
            throw new RuntimeException('Sensitive columns cannot be queried.');
        }

        preg_match_all('/\b(?:from|join)\s+([a-z_][a-z0-9_]*)/i', $normalized, $matches);
        $tables = $matches[1] ?? [];

        if (empty($tables)) {
            throw new RuntimeException('Query must reference at least one known table.');
        }

        foreach ($tables as $table) {
            if (! array_key_exists($table, $allowedTables)) {
                throw new RuntimeException("Table '{$table}' is not available to the chatbot.");
            }
        }
    }

    private function ask(string $system, string $user, bool $jsonMode = false): string
    {
        $payload = [
            'model' => config('services.groq.model'),
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
        ];

        if ($jsonMode) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        $apiKey = config('services.groq.api_key');
        if (! is_string($apiKey) || $apiKey === '') {
            throw new RuntimeException('GROQ_API_KEY is not configured.');
        }

        $response = Http::baseUrl(config('services.groq.base_url'))
            ->withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->post('/chat/completions', $payload)
            ->throw()
            ->json();

        $text = data_get($response, 'choices.0.message.content');

        if (! is_string($text) || $text === '') {
            throw new RuntimeException('The chatbot provider returned no response.');
        }

        return $text;
    }
}
