<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $spicyLevel = $request->query('spicy_level');
        $maxCalories = $request->query('max_calories');

        $foodItems = FoodItem::query()
            ->where('status', 'active')
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($minPrice !== null && $minPrice !== '', fn ($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice !== null && $maxPrice !== '', fn ($q) => $q->where('price', '<=', $maxPrice))
            ->when($spicyLevel !== null && $spicyLevel !== '', fn ($q) => $q->where('spicy_level', '<=', $spicyLevel))
            ->when($maxCalories !== null && $maxCalories !== '', fn ($q) => $q->where('calories', '<=', $maxCalories))
            ->get();

        $beverages = Beverage::query()
            ->where('status', 'active')
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($minPrice !== null && $minPrice !== '', fn ($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice !== null && $maxPrice !== '', fn ($q) => $q->where('price', '<=', $maxPrice))
            ->when($maxCalories !== null && $maxCalories !== '', fn ($q) => $q->where('calories', '<=', $maxCalories))
            ->get();

        $categories = Category::all();

        $favoriteFoodIds = $request->user()->favorites()
            ->where('itemable_type', FoodItem::class)->pluck('itemable_id')->all();
        $favoriteBeverageIds = $request->user()->favorites()
            ->where('itemable_type', Beverage::class)->pluck('itemable_id')->all();

        return view('menu.index', compact(
            'foodItems', 'beverages', 'categories', 'search', 'categoryId',
            'favoriteFoodIds', 'favoriteBeverageIds',
            'minPrice', 'maxPrice', 'spicyLevel', 'maxCalories'
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
