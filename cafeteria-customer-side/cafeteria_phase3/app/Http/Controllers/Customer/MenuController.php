<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $foodItems = FoodItem::query()
            ->where('status', 'active')
            ->with('category')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->get();

        $beverages = Beverage::query()
            ->where('status', 'active')
            ->with('category')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->get();

        $categories = \App\Models\Category::all();

        return view('menu.index', compact('foodItems', 'beverages', 'categories', 'search', 'categoryId'));
    }

    public function showFood(FoodItem $foodItem)
    {
        return view('menu.food-show', compact('foodItem'));
    }

    public function showBeverage(Beverage $beverage)
    {
        return view('menu.beverage-show', compact('beverage'));
    }
}
