<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Edit Food Item</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.food-items.update', $foodItem) }}" method="POST" enctype="multipart/form-data" class="card-plain" style="padding:1.5rem;">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1rem;">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" value="{{ old('name', $foodItem->name) }}" required />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="category_id" value="Category" />
                <select id="category_id" name="category_id" class="input-cc" style="width:100%;" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $foodItem->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="2" class="input-cc" style="width:100%;">{{ old('description', $foodItem->description) }}</textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <x-input-label for="price" value="Price (EGP)" />
                    <x-text-input id="price" name="price" type="number" step="0.01" value="{{ old('price', $foodItem->price) }}" required />
                </div>
                <div>
                    <x-input-label for="calories" value="Calories" />
                    <x-text-input id="calories" name="calories" type="number" value="{{ old('calories', $foodItem->calories) }}" />
                </div>
                <div>
                    <x-input-label for="spicy_level" value="Spicy level (0-5)" />
                    <x-text-input id="spicy_level" name="spicy_level" type="number" min="0" max="5" value="{{ old('spicy_level', $foodItem->spicy_level) }}" required />
                </div>
                <div>
                    <x-input-label for="preparation_time" value="Prep time (min)" />
                    <x-text-input id="preparation_time" name="preparation_time" type="number" value="{{ old('preparation_time', $foodItem->preparation_time) }}" />
                </div>
                <div>
                    <x-input-label for="available_quantity" value="Available quantity" />
                    <x-text-input id="available_quantity" name="available_quantity" type="number" value="{{ old('available_quantity', $foodItem->available_quantity) }}" required />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="input-cc" style="width:100%;" required>
                        <option value="active" {{ old('status', $foodItem->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $foodItem->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="ingredients" value="Ingredients (comma separated)" />
                <textarea id="ingredients" name="ingredients" rows="2" class="input-cc" style="width:100%;">{{ old('ingredients', $foodItem->ingredients) }}</textarea>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="image" value="Photo" />
                <input type="file" id="image" name="image" accept="image/*" class="input-cc" style="width:100%;">
                @if ($foodItem->image)
                    <img src="{{ asset('storage/' . $foodItem->image) }}" style="margin-top:0.5rem; width:80px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--cc-line);">
                @endif
            </div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <x-primary-button>Update</x-primary-button>
                <a href="{{ route('admin.food-items.index') }}" style="font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
