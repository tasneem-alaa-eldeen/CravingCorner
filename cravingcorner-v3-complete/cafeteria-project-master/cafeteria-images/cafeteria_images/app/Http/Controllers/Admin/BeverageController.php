<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Beverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $this->validated($request);
        $validated['image'] = $this->storeImage($request);

        Beverage::create($validated);

        return redirect()->route('admin.beverages.index')->with('status', 'Beverage created.');
    }

    public function edit(Beverage $beverage)
    {
        $categories = Category::where('type', 'beverage')->get();
        return view('admin.beverages.edit', compact('beverage', 'categories'));
    }

    public function update(Request $request, Beverage $beverage)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($beverage->image) {
                Storage::disk('public')->delete($beverage->image);
            }
            $validated['image'] = $this->storeImage($request);
        }

        $beverage->update($validated);

        return redirect()->route('admin.beverages.index')->with('status', 'Beverage updated.');
    }

    public function destroy(Beverage $beverage)
    {
        if ($beverage->image) {
            Storage::disk('public')->delete($beverage->image);
        }

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
            'ingredients' => ['nullable', 'string'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:50'],
            'temperature' => ['required', 'in:hot,cold'],
            'available_quantity' => ['required', 'integer', 'min:0'],
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
