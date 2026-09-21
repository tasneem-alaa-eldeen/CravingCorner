<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:food,beverage'],
            'id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $modelClass = $validated['type'] === 'food' ? FoodItem::class : Beverage::class;
        $item = $modelClass::findOrFail($validated['id']);

        // One review per user per item: resubmitting updates the existing one.
        \App\Models\Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'itemable_id' => $item->id,
                'itemable_type' => $modelClass,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return back()->with('status', 'Thanks for your review!');
    }
}
