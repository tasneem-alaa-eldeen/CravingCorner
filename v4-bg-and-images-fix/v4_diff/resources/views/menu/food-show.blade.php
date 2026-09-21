<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">{{ $foodItem->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="card-ticket" style="padding:1.5rem;">
            @if ($foodItem->image)
                <img src="{{ asset('storage/' . $foodItem->image) }}" style="width:100%; height:220px; object-fit:contain; border-radius:8px; margin-bottom:1rem; background: var(--cc-paper); border:1px solid var(--cc-line); padding:0.6rem;" alt="{{ $foodItem->name }}">
            @else
                <div style="width:100%; height:220px; border-radius:8px; margin-bottom:1rem; background: var(--cc-paper); border:1px dashed var(--cc-line); display:flex; align-items:center; justify-content:center; font-size:3rem;">🍽️</div>
            @endif
            <p style="font-size:0.8rem; color: var(--cc-text-muted);">{{ $foodItem->category->name ?? '' }}</p>
            @if ($avgRating)
                <p style="font-size:0.875rem; color: var(--cc-mustard-dark); margin-top:0.25rem;">
                    {{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5 - round($avgRating)) }}
                    <span style="color: var(--cc-text-muted);">({{ number_format($avgRating, 1) }} / 5, {{ $foodItem->reviews->count() }} reviews)</span>
                </p>
            @else
                <p style="font-size:0.875rem; color: var(--cc-text-muted); margin-top:0.25rem;">No reviews yet.</p>
            @endif

            <p style="margin-top:0.75rem; color: var(--cc-ink);">{{ $foodItem->description }}</p>

            <dl style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; font-size:0.875rem; margin-top:1rem;">
                <div><dt style="color: var(--cc-text-muted);">Price</dt><dd class="font-mono">{{ number_format($foodItem->price, 2) }} EGP</dd></div>
                <div><dt style="color: var(--cc-text-muted);">Calories</dt><dd>{{ $foodItem->calories ?? '—' }}</dd></div>
                <div><dt style="color: var(--cc-text-muted);">Spicy level</dt><dd>{{ $foodItem->spicy_level }}/5</dd></div>
                <div><dt style="color: var(--cc-text-muted);">Prep time</dt><dd>{{ $foodItem->preparation_time ?? '—' }} min</dd></div>
            </dl>

            @if ($foodItem->ingredients)
                <p style="margin-top:0.75rem; font-size:0.875rem;"><span style="color: var(--cc-text-muted);">Ingredients:</span> {{ $foodItem->ingredients }}</p>
            @endif

            <form action="{{ route('cart.add') }}" method="POST" style="margin-top:1.25rem; display:flex; align-items:center; gap:0.75rem;">
                @csrf
                <input type="hidden" name="type" value="food">
                <input type="hidden" name="id" value="{{ $foodItem->id }}">
                <input type="number" name="quantity" value="1" min="1" style="width:80px;" class="input-cc">
                <x-primary-button>Add to cart</x-primary-button>
            </form>
        </div>

        <div class="card-plain" style="padding:1.5rem; margin-top:1.5rem;">
            <h3 class="font-display" style="font-weight:700; margin-bottom:0.75rem;">Leave a review</h3>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="food">
                <input type="hidden" name="id" value="{{ $foodItem->id }}">

                <label style="display:block; font-size:0.8125rem; color: var(--cc-text-muted); margin-bottom:0.25rem;">Rating</label>
                <select name="rating" required class="input-cc" style="width:100px; margin-bottom:0.75rem;">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} ★</option>
                    @endfor
                </select>

                <label style="display:block; font-size:0.8125rem; color: var(--cc-text-muted); margin-bottom:0.25rem;">Comment (optional)</label>
                <textarea name="comment" rows="2" class="input-cc" style="width:100%; margin-bottom:0.75rem;"></textarea>

                <button type="submit" class="btn-ink">Submit review</button>
            </form>

            @if ($foodItem->reviews->isNotEmpty())
                <div style="margin-top:1.25rem; border-top:1px solid var(--cc-line);">
                    @foreach ($foodItem->reviews as $review)
                        <div style="padding:0.75rem 0; border-bottom:1px solid var(--cc-line); font-size:0.875rem;">
                            <p style="font-weight:600;">{{ $review->user->name }} — <span style="color: var(--cc-mustard-dark);">{{ str_repeat('★', $review->rating) }}</span></p>
                            @if ($review->comment)
                                <p style="color: var(--cc-text-muted); margin-top:0.25rem;">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <a href="{{ route('menu.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to menu</a>
    </div>
</x-app-layout>
