<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * "For You" page: every active item scored against the customer's
     * saved preferences + order history, sorted by match %.
     * Reuses the existing RecommendationService (no scoring logic
     * duplicated here).
     */
    public function index(Request $request, RecommendationService $recommendations)
    {
        $type = $request->query('type'); // null | 'food' | 'beverage'

        $scored = $recommendations->scoreItemsFor($request->user());

        if ($type === 'food' || $type === 'beverage') {
            $scored = $scored->where('type', $type)->values();
        }

        // Simple buckets so the view can show "Best matches" vs "Worth a try"
        $topMatches   = $scored->where('match', '>=', 75)->values();
        $otherMatches = $scored->where('match', '<', 75)->values();

        $hasPreferences = (bool) $request->user()->preference;

        return view('recommendations.index', [
            'topMatches' => $topMatches,
            'otherMatches' => $otherMatches,
            'type' => $type,
            'hasPreferences' => $hasPreferences,
        ]);
    }
}
