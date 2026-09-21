<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\Category;
use Illuminate\Http\Request;

class BeverageController extends Controller
{
    public function index()
    {
        $beverages = Beverage::with('category')->latest()->get();
        return view('admin.beverages.index', compact('beverages'));
    }

    public function create()
    {
        $categories = Category::where('type', 'beverage')->get();
        return view('admin.beverages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Beverage::create($this->validated($request));

        return redirect()->route('admin.beverages.index')->with('status', 'Beverage created.');
    }

    public function edit(Beverage $beverage)
    {
        $categories = Category::where('type', 'beverage')->get();
        return view('admin.beverages.edit', compact('beverage', 'categories'));
    }

    public function update(Request $request, Beverage $beverage)
    {
        $beverage->update($this->validated($request));

        return redirect()->route('admin.beverages.index')->with('status', 'Beverage updated.');
    }

    public function destroy(Beverage $beverage)
    {
        $beverage->delete();

        return redirect()->route('admin.beverages.index')->with('status', 'Beverage deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'size' => ['nullable', 'string', 'max:50'],
            'ingredients' => ['nullable', 'string'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'temperature' => ['required', 'in:hot,cold'],
            'available_quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
