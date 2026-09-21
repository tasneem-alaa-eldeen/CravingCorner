<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">My Preferences</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <form action="{{ route('preferences.update') }}" method="POST" class="card-plain" style="padding:1.5rem;">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <x-input-label value="Favorite categories" />
                <div style="display:flex; flex-direction:column; gap:0.35rem;">
                    @foreach ($categories as $cat)
                        <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.875rem;">
                            <input type="checkbox" name="favorite_categories[]" value="{{ $cat->id }}"
                                {{ in_array($cat->id, $preference->favorite_categories ?? []) ? 'checked' : '' }}>
                            {{ $cat->name }} ({{ $cat->type }})
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom:1.25rem;">
                <x-input-label for="preferred_taste" value="Preferred taste" />
                <x-text-input id="preferred_taste" name="preferred_taste" value="{{ old('preferred_taste', $preference->preferred_taste ?? '') }}" placeholder="e.g. savory, sweet, cheesy" />
            </div>

            <div style="margin-bottom:1.25rem;">
                <x-input-label for="price_preference" value="Max budget per item (EGP)" />
                <x-text-input id="price_preference" name="price_preference" type="number" value="{{ old('price_preference', $preference->price_preference ?? '') }}" />
            </div>

            <div style="margin-bottom:1.25rem;">
                <x-input-label for="spicy_level" value="Preferred spicy level (0-5)" />
                <x-text-input id="spicy_level" name="spicy_level" type="number" min="0" max="5" value="{{ old('spicy_level', $preference->spicy_level ?? '') }}" />
            </div>

            <div style="margin-bottom:1.25rem;">
                <x-input-label for="favorite_ingredients" value="Favorite ingredients (comma separated)" />
                <x-text-input id="favorite_ingredients" name="favorite_ingredients"
                    value="{{ old('favorite_ingredients', is_array($preference->favorite_ingredients ?? null) ? implode(', ', $preference->favorite_ingredients) : '') }}"
                    placeholder="e.g. chicken, cheese" />
            </div>

            <div style="margin-bottom:1.5rem;">
                <x-input-label for="disliked_ingredients" value="Disliked ingredients (comma separated)" />
                <x-text-input id="disliked_ingredients" name="disliked_ingredients"
                    value="{{ old('disliked_ingredients', is_array($preference->disliked_ingredients ?? null) ? implode(', ', $preference->disliked_ingredients) : '') }}"
                    placeholder="e.g. mushroom, olives" />
            </div>

            <x-primary-button style="width:100%;">Save preferences</x-primary-button>
        </form>
    </div>
</x-app-layout>
