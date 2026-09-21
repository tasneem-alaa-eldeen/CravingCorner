<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Edit Beverage</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.beverages.update', $beverage) }}" method="POST" enctype="multipart/form-data" class="card-plain" style="padding:1.5rem;">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1rem;">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" value="{{ old('name', $beverage->name) }}" required />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="category_id" value="Category" />
                <select id="category_id" name="category_id" class="input-cc" style="width:100%;" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $beverage->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="2" class="input-cc" style="width:100%;">{{ old('description', $beverage->description) }}</textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <x-input-label for="price" value="Price (EGP)" />
                    <x-text-input id="price" name="price" type="number" step="0.01" value="{{ old('price', $beverage->price) }}" required />
                </div>
                <div>
                    <x-input-label for="size" value="Size" />
                    <x-text-input id="size" name="size" value="{{ old('size', $beverage->size) }}" placeholder="e.g. Regular, Large" />
                </div>
                <div>
                    <x-input-label for="calories" value="Calories" />
                    <x-text-input id="calories" name="calories" type="number" value="{{ old('calories', $beverage->calories) }}" />
                </div>
                <div>
                    <x-input-label for="temperature" value="Temperature" />
                    <select id="temperature" name="temperature" class="input-cc" style="width:100%;" required>
                        <option value="hot" {{ old('temperature', $beverage->temperature) == 'hot' ? 'selected' : '' }}>Hot</option>
                        <option value="cold" {{ old('temperature', $beverage->temperature) == 'cold' ? 'selected' : '' }}>Cold</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="available_quantity" value="Available quantity" />
                    <x-text-input id="available_quantity" name="available_quantity" type="number" value="{{ old('available_quantity', $beverage->available_quantity) }}" required />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="input-cc" style="width:100%;" required>
                        <option value="active" {{ old('status', $beverage->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $beverage->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="ingredients" value="Ingredients (comma separated)" />
                <textarea id="ingredients" name="ingredients" rows="2" class="input-cc" style="width:100%;">{{ old('ingredients', $beverage->ingredients) }}</textarea>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="image" value="Photo" />
                <input type="file" id="image" name="image" accept="image/*" class="input-cc" style="width:100%;">
                @if ($beverage->image)
                    <img src="{{ asset('storage/' . $beverage->image) }}" style="margin-top:0.5rem; width:80px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--cc-line);">
                @endif
            </div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <x-primary-button>Update</x-primary-button>
                <a href="{{ route('admin.beverages.index') }}" style="font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
