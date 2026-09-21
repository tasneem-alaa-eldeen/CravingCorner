<?php

namespace App\Services;

use App\Models\Beverage;
use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Scores every active food item + beverage against the customer's
     * preferences and order history. Returns a collection sorted by
     * match percentage (highest first), each entry shaped as:
     * ['item' => Model, 'type' => 'food'|'beverage', 'match' => int 0-100]
     */
    public function scoreItemsFor(User $user): Collection
    {
        $preference = $user->preference;
        $orderedNames = $user->orders()
            ->with('items.itemable')
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('itemable.name')
            ->filter()
            ->countBy();

        $foodItems = FoodItem::where('status', 'active')->get()
            ->map(fn ($item) => $this->score($item, 'food', $preference, $orderedNames));

        $beverages = Beverage::where('status', 'active')->get()
            ->map(fn ($item) => $this->score($item, 'beverage', $preference, $orderedNames));

        return $foodItems->concat($beverages)->sortByDesc('match')->values();
    }

    private function score($item, string $type, $preference, Collection $orderedNames): array
    {
        // Start at a neutral baseline so items still get a reasonable
        // score even when the customer has no preferences saved yet.
        $points = 40;
        $max = 40;

        if ($preference) {
            $max += 30;
            if (in_array($item->category_id, $preference->favorite_categories ?? [])) {
                $points += 30;
            }

            if ($preference->price_preference) {
                $max += 15;
                if ($item->price <= (float) $preference->price_preference) {
                    $points += 15;
                }
            }

            if ($type === 'food' && $preference->spicy_level !== null) {
                $max += 15;
                $diff = abs($item->spicy_level - $preference->spicy_level);
                $points += max(0, 15 - ($diff * 4));
            }

            $ingredients = strtolower($item->ingredients ?? '');

            foreach ($preference->favorite_ingredients ?? [] as $liked) {
                $max += 5;
                if (str_contains($ingredients, strtolower($liked))) {
                    $points += 5;
                }
            }

            foreach ($preference->disliked_ingredients ?? [] as $disliked) {
                if (str_contains($ingredients, strtolower($disliked))) {
                    $points -= 40;
                }
            }
        }

        // A small boost for items the customer has ordered before.
        if ($orderedNames->has($item->name)) {
            $points += 10;
            $max += 10;
        }

        $percentage = $max > 0 ? (int) round(max(0, min($points, $max)) / $max * 100) : 50;

        return [
            'item' => $item,
            'type' => $type,
            'match' => $percentage,
        ];
    }
}
