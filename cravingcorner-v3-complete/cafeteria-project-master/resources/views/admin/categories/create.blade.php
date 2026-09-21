<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">New Category</h2>
    </x-slot>

    <div class="py-8 max-w-lg mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="card-plain" style="padding:1.5rem;">
            @csrf
            <div style="margin-bottom:1rem;">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" value="{{ old('name') }}" required />
                <x-input-error :messages="$errors->get('name')" />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="type" value="Type" />
                <select id="type" name="type" class="input-cc" style="width:100%;" required>
                    <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>Food</option>
                    <option value="beverage" {{ old('type') == 'beverage' ? 'selected' : '' }}>Beverage</option>
                </select>
                <x-input-error :messages="$errors->get('type')" />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3" class="input-cc" style="width:100%;">{{ old('description') }}</textarea>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="image" value="Cover Photo" />
                <input type="file" id="image" name="image" accept="image/*" class="input-cc" style="width:100%;">
                <x-input-error :messages="$errors->get('image')" />
            </div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <x-primary-button>Save</x-primary-button>
                <a href="{{ route('admin.categories.index') }}" style="font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
