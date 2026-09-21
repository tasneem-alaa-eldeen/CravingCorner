<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Preferences</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <form action="{{ route('preferences.update') }}" method="POST" class="bg-white shadow-sm rounded-lg p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Favorite categories</label>
                <div class="space-y-1">
                    @foreach ($categories as $cat)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="favorite_categories[]" value="{{ $cat->id }}"
                                {{ in_array($cat->id, $preference->favorite_categories ?? []) ? 'checked' : '' }}>
                            {{ $cat->name }} ({{ $cat->type }})
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred taste</label>
                <input type="text" name="preferred_taste" value="{{ old('preferred_taste', $preference->preferred_taste ?? '') }}"
                    placeholder="e.g. savory, sweet, cheesy" style="width:100%;" class="border-gray-300 rounded-md text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max budget per item (EGP)</label>
                <input type="number" name="price_preference" value="{{ old('price_preference', $preference->price_preference ?? '') }}"
                    style="width:100%;" class="border-gray-300 rounded-md text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred spicy level (0-5)</label>
                <input type="number" name="spicy_level" min="0" max="5" value="{{ old('spicy_level', $preference->spicy_level ?? '') }}"
                    style="width:100%;" class="border-gray-300 rounded-md text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Favorite ingredients (comma separated)</label>
                <input type="text" name="favorite_ingredients" value="{{ old('favorite_ingredients', is_array($preference->favorite_ingredients ?? null) ? implode(', ', $preference->favorite_ingredients) : '') }}"
                    placeholder="e.g. chicken, cheese" style="width:100%;" class="border-gray-300 rounded-md text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Disliked ingredients (comma separated)</label>
                <input type="text" name="disliked_ingredients" value="{{ old('disliked_ingredients', is_array($preference->disliked_ingredients ?? null) ? implode(', ', $preference->disliked_ingredients) : '') }}"
                    placeholder="e.g. mushroom, olives" style="width:100%;" class="border-gray-300 rounded-md text-sm">
            </div>

            <button type="submit" style="display:block; width:100%; background-color:#4f46e5; color:#ffffff; padding:10px; border-radius:6px; font-weight:600; border:none; cursor:pointer;">
                Save preferences
            </button>
        </form>
    </div>
</x-app-layout>
