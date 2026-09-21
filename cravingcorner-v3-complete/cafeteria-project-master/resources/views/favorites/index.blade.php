<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">My Favorites</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse ($favorites as $favorite)
                @php $item = $favorite->itemable; @endphp
                @if ($item)
                    <div class="card-ticket" style="padding:1rem;">
                        <p style="font-weight:700; color: var(--cc-ink);">{{ $item->name }}</p>
                        <p class="font-mono" style="margin-top:0.5rem;">{{ number_format($item->price, 2) }} EGP</p>

                        <form action="{{ route('favorites.toggle') }}" method="POST" style="margin-top:0.75rem;">
                            @csrf
                            <input type="hidden" name="type" value="{{ $favorite->itemable_type === \App\Models\Beverage::class ? 'beverage' : 'food' }}">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="btn-text-clay">Remove from favorites</button>
                        </form>
                    </div>
                @endif
            @empty
                <p style="color: var(--cc-text-muted); grid-column:1 / -1;">You haven't favorited anything yet.</p>
            @endforelse
        </div>

        <a href="{{ route('menu.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to menu</a>
    </div>
</x-app-layout>
