<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Edit Category</h2>
    </x-slot>

    <div class="py-8 max-w-lg mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="card-plain" style="padding:1.5rem;">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1rem;">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" value="{{ old('name', $category->name) }}" required />
                <x-input-error :messages="$errors->get('name')" />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="type" value="Type" />
                <select id="type" name="type" class="input-cc" style="width:100%;" required>
                    <option value="food" {{ old('type', $category->type) == 'food' ? 'selected' : '' }}>Food</option>
                    <option value="beverage" {{ old('type', $category->type) == 'beverage' ? 'selected' : '' }}>Beverage</option>
                </select>
                <x-input-error :messages="$errors->get('type')" />
            </div>
            <div style="margin-bottom:1rem;">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3" class="input-cc" style="width:100%;">{{ old('description', $category->description) }}</textarea>
            </div>
            <div style="margin-bottom:1.25rem;">
                <x-input-label for="image" value="Cover Photo" />
                <input type="file" id="image" name="image" accept="image/*" class="input-cc" style="width:100%;">
                @if ($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" style="margin-top:0.5rem; width:100px; height:70px; object-fit:cover; border-radius:8px; border:1px solid var(--cc-line);">
                @endif
                <x-input-error :messages="$errors->get('image')" />
            </div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <x-primary-button>Update</x-primary-button>
                <a href="{{ route('admin.categories.index') }}" style="font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
