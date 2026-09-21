<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->with('itemable')->latest()->get();

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:food,beverage'],
            'id' => ['required', 'integer'],
        ]);

        $modelClass = $validated['type'] === 'food' ? FoodItem::class : Beverage::class;
        $item = $modelClass::findOrFail($validated['id']);

        $existing = $request->user()->favorites()
            ->where('itemable_type', $modelClass)
            ->where('itemable_id', $item->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'Removed from favorites.';
        } else {
            $request->user()->favorites()->create([
                'itemable_type' => $modelClass,
                'itemable_id' => $item->id,
            ]);
            $status = 'Added to favorites.';
        }

        return back()->with('status', $status);
    }
}
