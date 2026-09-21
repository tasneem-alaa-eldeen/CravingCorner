<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    public function index()
    {
        $foodItems = FoodItem::with('category')->latest()->get();
        return view('admin.food-items.index', compact('foodItems'));
    }

    public function create()
    {
        $categories = Category::where('type', 'food')->get();
        return view('admin.food-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['image'] = $this->storeImage($request);

        FoodItem::create($validated);

        return redirect()->route('admin.food-items.index')->with('status', 'Food item created.');
    }

    public function edit(FoodItem $foodItem)
    {
        $categories = Category::where('type', 'food')->get();
        return view('admin.food-items.edit', compact('foodItem', 'categories'));
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($foodItem->image) {
                Storage::disk('public')->delete($foodItem->image);
            }
            $validated['image'] = $this->storeImage($request);
        }

        $foodItem->update($validated);

        return redirect()->route('admin.food-items.index')->with('status', 'Food item updated.');
    }

    public function destroy(FoodItem $foodItem)
    {
        if ($foodItem->image) {
            Storage::disk('public')->delete($foodItem->image);
        }

        $foodItem->delete();

        return redirect()->route('admin.food-items.index')->with('status', 'Food item deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'ingredients' => ['nullable', 'string'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'spicy_level' => ['required', 'integer', 'min:0', 'max:5'],
            'available_quantity' => ['required', 'integer', 'min:0'],
            'preparation_time' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('menu-images', 'public');
    }
}
