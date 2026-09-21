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

    // ---- CUSTOMER: no SQL access. AI only sees pre-computed context ---

    private function respondAsCustomer(User $user, string $message): string
    {
        // Every active item, scored + enriched with the details needed for
        // recommendations, comparisons ("compare X and Y"), and combo
        // suggestions ("meal + drink"). The AI never queries the database
        // itself — this fixed list is the only "data" it can ever mention.
        $catalog = $this->recommendations->scoreItemsFor($user)
            ->map(fn ($row) => [
                'name' => $row['item']->name,
                'type' => $row['type'],
                'category' => $row['item']->category->name ?? null,
                'price' => (float) $row['item']->price,
                'calories' => $row['item']->calories,
                'spicy_level' => $row['type'] === 'food' ? $row['item']->spicy_level : null,
                'match_percentage' => $row['match'],
            ])
            ->take(25) // keep the prompt small; scoreItemsFor is already sorted best-first
            ->values();

        $context = json_encode($catalog, JSON_THROW_ON_ERROR);

        return $this->ask(
            system: "You are the cafeteria's customer assistant for {$user->name}. You may ONLY recommend, compare, or combine items from the list below — never mention or invent anything else. "
                ."For recommendations, mention the match percentage naturally. For comparisons (e.g. \"compare X and Y\"), contrast price, calories, spicy level, and category using the listed items only. "
                ."For combo requests (e.g. \"meal and a drink\"), pick one food item + one beverage from the list that pair well and explain why.\n\n"
                ."Available items:\n{$context}",
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
    public function suggestItemNames(\App\Models\User $user, string $naturalLanguageQuery): array
{
    $catalog = $this->recommendations->scoreItemsFor($user)
        ->map(fn ($row) => [
            'name' => $row['item']->name,
            'type' => $row['type'],
            'category' => $row['item']->category->name ?? null,
            'price' => (float) $row['item']->price,
            'calories' => $row['item']->calories,
            'spicy_level' => $row['type'] === 'food' ? $row['item']->spicy_level : null,
        ])
        ->values();

    $context = json_encode($catalog, JSON_THROW_ON_ERROR);

    $decoded = json_decode($this->ask(
        system: 'You match a natural-language food/drink search against a fixed catalog. Reply with ONLY a JSON object: {"matches": [exact item names from the catalog that fit, best first]}. Never invent a name not in the catalog. Return at most 8 matches.',
        user: "Catalog: {$context}\nSearch: {$naturalLanguageQuery}",
        jsonMode: true,
    ), true);

    return is_array($decoded) ? array_slice($decoded['matches'] ?? [], 0, 8) : [];
}

}
