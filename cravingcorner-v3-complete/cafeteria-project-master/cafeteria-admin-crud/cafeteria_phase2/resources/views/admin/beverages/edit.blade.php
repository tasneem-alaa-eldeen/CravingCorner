<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Beverage</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.beverages.update', $beverage) }}" method="POST" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $beverage->name) }}" required />
            </div>
            <div>
                <x-input-label for="category_id" value="Category" />
                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $beverage->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $beverage->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="price" value="Price (EGP)" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('price', $beverage->price) }}" required />
                </div>
                <div>
                    <x-input-label for="size" value="Size" />
                    <x-text-input id="size" name="size" class="mt-1 block w-full" value="{{ old('size', $beverage->size) }}" placeholder="e.g. Regular, Large" />
                </div>
                <div>
                    <x-input-label for="calories" value="Calories" />
                    <x-text-input id="calories" name="calories" type="number" class="mt-1 block w-full" value="{{ old('calories', $beverage->calories) }}" />
                </div>
                <div>
                    <x-input-label for="temperature" value="Temperature" />
                    <select id="temperature" name="temperature" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        <option value="hot" {{ old('temperature', $beverage->temperature) == 'hot' ? 'selected' : '' }}>Hot</option>
                        <option value="cold" {{ old('temperature', $beverage->temperature) == 'cold' ? 'selected' : '' }}>Cold</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="available_quantity" value="Available quantity" />
                    <x-text-input id="available_quantity" name="available_quantity" type="number" class="mt-1 block w-full" value="{{ old('available_quantity', $beverage->available_quantity) }}" required />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        <option value="active" {{ old('status', $beverage->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $beverage->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div>
                <x-input-label for="ingredients" value="Ingredients (comma separated)" />
                <textarea id="ingredients" name="ingredients" rows="2" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('ingredients', $beverage->ingredients) }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <x-primary-button>Save</x-primary-button>
                <a href="{{ route('admin.beverages.index') }}" class="text-sm text-gray-500">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
