<?php
// Add this PUBLIC method inside app/Services/ChatbotService.php
// (anywhere inside the class, e.g. right after respond()):

/**
 * Used by the menu's search box when a plain keyword search finds
 * nothing. Same "no direct DB access" rule applies: the AI only picks
 * names from the catalog we hand it — it can't invent an item.
 */
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
