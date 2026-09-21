<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">New Food Item</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.food-items.store') }}" method="POST" enctype="multipart/form-data" class="card-plain" style="padding:1.5rem;">
            @csrf
            <div style="margin-bottom:1rem;">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" value="{{ old('name') }}" required />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="category_id" value="Category" />
                <select id="category_id" name="category_id" class="input-cc" style="width:100%;" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="2" class="input-cc" style="width:100%;">{{ old('description') }}</textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <x-input-label for="price" value="Price (EGP)" />
                    <x-text-input id="price" name="price" type="number" step="0.01" value="{{ old('price') }}" required />
                </div>
                <div>
                    <x-input-label for="calories" value="Calories" />
                    <x-text-input id="calories" name="calories" type="number" value="{{ old('calories') }}" />
                </div>
                <div>
                    <x-input-label for="spicy_level" value="Spicy level (0-5)" />
                    <x-text-input id="spicy_level" name="spicy_level" type="number" min="0" max="5" value="{{ old('spicy_level', 0) }}" required />
                </div>
                <div>
                    <x-input-label for="preparation_time" value="Prep time (min)" />
                    <x-text-input id="preparation_time" name="preparation_time" type="number" value="{{ old('preparation_time') }}" />
                </div>
                <div>
                    <x-input-label for="available_quantity" value="Available quantity" />
                    <x-text-input id="available_quantity" name="available_quantity" type="number" value="{{ old('available_quantity', 0) }}" required />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="input-cc" style="width:100%;" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="ingredients" value="Ingredients (comma separated)" />
                <textarea id="ingredients" name="ingredients" rows="2" class="input-cc" style="width:100%;">{{ old('ingredients') }}</textarea>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="image" value="Photo" />
                <input type="file" id="image" name="image" accept="image/*" class="input-cc" style="width:100%;">
            </div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <x-primary-button>Save</x-primary-button>
                <a href="{{ route('admin.food-items.index') }}" style="font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
