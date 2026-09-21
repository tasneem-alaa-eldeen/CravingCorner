<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\Category;
use App\Models\FoodItem;
use App\Services\ChatbotService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, ChatbotService $chatbot)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $spicyLevel = $request->query('spicy_level');
        $maxCalories = $request->query('max_calories');

        $foodQuery = FoodItem::query()
            ->where('status', 'active')
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($minPrice !== null && $minPrice !== '', fn ($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice !== null && $maxPrice !== '', fn ($q) => $q->where('price', '<=', $maxPrice))
            ->when($spicyLevel !== null && $spicyLevel !== '', fn ($q) => $q->where('spicy_level', '<=', $spicyLevel))
            ->when($maxCalories !== null && $maxCalories !== '', fn ($q) => $q->where('calories', '<=', $maxCalories));

        $beverageQuery = Beverage::query()
            ->where('status', 'active')
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($minPrice !== null && $minPrice !== '', fn ($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice !== null && $maxPrice !== '', fn ($q) => $q->where('price', '<=', $maxPrice))
            ->when($maxCalories !== null && $maxCalories !== '', fn ($q) => $q->where('calories', '<=', $maxCalories));

        $aiSearchUsed = false;

        if ($search) {
            $exactFoodItems = (clone $foodQuery)->where('name', 'like', "%{$search}%")->get();
            $exactBeverages = (clone $beverageQuery)->where('name', 'like', "%{$search}%")->get();

            if ($exactFoodItems->isEmpty() && $exactBeverages->isEmpty()) {
                // No exact name match — ask the AI which items (from the
                // real catalog only) best fit what the customer described.
                $matchedNames = $chatbot->suggestItemNames($request->user(), $search);
                $aiSearchUsed = true;

                $foodItems = (clone $foodQuery)->whereIn('name', $matchedNames)->get();
                $beverages = (clone $beverageQuery)->whereIn('name', $matchedNames)->get();
            } else {
                $foodItems = $exactFoodItems;
                $beverages = $exactBeverages;
            }
        } else {
            $foodItems = $foodQuery->get();
            $beverages = $beverageQuery->get();
        }

        $categories = Category::all();

        $favoriteFoodIds = $request->user()->favorites()
            ->where('itemable_type', FoodItem::class)->pluck('itemable_id')->all();
        $favoriteBeverageIds = $request->user()->favorites()
            ->where('itemable_type', Beverage::class)->pluck('itemable_id')->all();

        return view('menu.index', compact(
            'foodItems', 'beverages', 'categories', 'search', 'categoryId',
            'favoriteFoodIds', 'favoriteBeverageIds',
            'minPrice', 'maxPrice', 'spicyLevel', 'maxCalories', 'aiSearchUsed'
        ));
    }

    public function showFood(FoodItem $foodItem)
    {
        $foodItem->load(['reviews.user']);
        $avgRating = $foodItem->reviews->avg('rating');

        return view('menu.food-show', compact('foodItem', 'avgRating'));
    }

    public function showBeverage(Beverage $beverage)
    {
        $beverage->load(['reviews.user']);
        $avgRating = $beverage->reviews->avg('rating');

        return view('menu.beverage-show', compact('beverage', 'avgRating'));
    }
}
