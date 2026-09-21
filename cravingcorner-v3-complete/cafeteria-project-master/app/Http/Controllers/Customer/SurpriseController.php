<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class SurpriseController extends Controller
{
    public function __invoke(Request $request, RecommendationService $recommendations)
    {
        // Pick randomly among the top 5 matches, so it's not always
        // the exact same "best" item every time.
        $pick = $recommendations->scoreItemsFor($request->user())
            ->take(5)
            ->random();

        return $pick['type'] === 'food'
            ? redirect()->route('menu.food.show', $pick['item'])->with('status', "Surprise! We think you'll like this ({$pick['match']}% match).")
            : redirect()->route('menu.beverage.show', $pick['item'])->with('status', "Surprise! We think you'll like this ({$pick['match']}% match).");
    }
}
