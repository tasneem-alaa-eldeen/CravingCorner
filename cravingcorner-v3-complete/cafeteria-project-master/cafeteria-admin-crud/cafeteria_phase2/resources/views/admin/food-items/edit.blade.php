<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Food Item</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.food-items.update', $foodItem) }}" method="POST" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $foodItem->name) }}" required />
            </div>
            <div>
                <x-input-label for="category_id" value="Category" />
                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $foodItem->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $foodItem->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="price" value="Price (EGP)" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('price', $foodItem->price) }}" required />
                </div>
                <div>
                    <x-input-label for="calories" value="Calories" />
                    <x-text-input id="calories" name="calories" type="number" class="mt-1 block w-full" value="{{ old('calories', $foodItem->calories) }}" />
                </div>
                <div>
                    <x-input-label for="spicy_level" value="Spicy level (0-5)" />
                    <x-text-input id="spicy_level" name="spicy_level" type="number" min="0" max="5" class="mt-1 block w-full" value="{{ old('spicy_level', $foodItem->spicy_level) }}" required />
                </div>
                <div>
                    <x-input-label for="preparation_time" value="Prep time (min)" />
                    <x-text-input id="preparation_time" name="preparation_time" type="number" class="mt-1 block w-full" value="{{ old('preparation_time', $foodItem->preparation_time) }}" />
                </div>
                <div>
                    <x-input-label for="available_quantity" value="Available quantity" />
                    <x-text-input id="available_quantity" name="available_quantity" type="number" class="mt-1 block w-full" value="{{ old('available_quantity', $foodItem->available_quantity) }}" required />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        <option value="active" {{ old('status', $foodItem->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $foodItem->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div>
                <x-input-label for="ingredients" value="Ingredients (comma separated)" />
                <textarea id="ingredients" name="ingredients" rows="2" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('ingredients', $foodItem->ingredients) }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <x-primary-button>Save</x-primary-button>
                <a href="{{ route('admin.food-items.index') }}" class="text-sm text-gray-500">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
