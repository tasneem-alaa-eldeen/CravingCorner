<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">✨ Recommended For You</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">

        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        @unless ($hasPreferences)
            <div class="card-plain" style="padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <p style="font-size:0.875rem; color: var(--cc-text-muted); margin:0;">
                    You haven't set your taste preferences yet — matches below use your order history only. Set preferences for sharper picks.
                </p>
                <a href="{{ route('preferences.edit') }}" class="btn-mustard" style="white-space:nowrap;">Set preferences</a>
            </div>
        @endunless

        <!-- Filter tabs -->
        <div style="display:flex; gap:0.5rem; margin-bottom:1.5rem;">
            <a href="{{ route('recommendations.index') }}" class="{{ is_null($type) ? 'btn-ink' : 'btn-outline' }}">All</a>
            <a href="{{ route('recommendations.index', ['type' => 'food']) }}" class="{{ $type === 'food' ? 'btn-ink' : 'btn-outline' }}">🍽️ Food</a>
            <a href="{{ route('recommendations.index', ['type' => 'beverage']) }}" class="{{ $type === 'beverage' ? 'btn-ink' : 'btn-outline' }}">🥤 Beverages</a>
        </div>

        <h3 class="font-display" style="font-weight:700; margin-bottom:0.75rem; color: var(--cc-ink);">Best matches</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8 cc-animate-stagger">
            @forelse ($topMatches as $row)
                @include('recommendations.partials.match-card', ['row' => $row])
            @empty
                <p style="color: var(--cc-text-muted); grid-column:1 / -1;">No strong matches yet — order a few things or set your preferences and check back.</p>
            @endforelse
        </div>

        @if ($otherMatches->isNotEmpty())
            <h3 class="font-display" style="font-weight:700; margin-bottom:0.75rem; color: var(--cc-ink);">Worth a try</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 cc-animate-stagger">
                @foreach ($otherMatches as $row)
                    @include('recommendations.partials.match-card', ['row' => $row])
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
