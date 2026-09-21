<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function edit(Request $request)
    {
        $preference = $request->user()->preference;
        $categories = Category::all();

        return view('preferences.edit', compact('preference', 'categories'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'favorite_categories' => ['nullable', 'array'],
            'preferred_taste' => ['nullable', 'string', 'max:255'],
            'price_preference' => ['nullable', 'numeric', 'min:0'],
            'spicy_level' => ['nullable', 'integer', 'min:0', 'max:5'],
            'favorite_ingredients' => ['nullable', 'string'],
            'disliked_ingredients' => ['nullable', 'string'],
        ]);

        $data = [
            'favorite_categories' => $validated['favorite_categories'] ?? [],
            'preferred_taste' => $validated['preferred_taste'] ?? null,
            'price_preference' => $validated['price_preference'] ?? null,
            'spicy_level' => $validated['spicy_level'] ?? null,
            // Turn the comma-separated textarea input into clean arrays.
            'favorite_ingredients' => $this->toArray($validated['favorite_ingredients'] ?? ''),
            'disliked_ingredients' => $this->toArray($validated['disliked_ingredients'] ?? ''),
        ];

        $request->user()->preference()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return back()->with('status', 'Preferences saved.');
    }

    private function toArray(string $commaSeparated): array
    {
        return collect(explode(',', $commaSeparated))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->values()
            ->all();
    }
}
