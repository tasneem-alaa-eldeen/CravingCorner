<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Category</h2>
    </x-slot>

    <div class="py-8 max-w-lg mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            @csrf
            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name') }}" required />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="type" value="Type" />
                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>Food</option>
                    <option value="beverage" {{ old('type') == 'beverage' ? 'selected' : '' }}>Beverage</option>
                </select>
                <x-input-error :messages="$errors->get('type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description') }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <x-primary-button>Save</x-primary-button>
                <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
