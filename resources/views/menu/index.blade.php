<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Menu</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        @if ($aiSearchUsed ?? false)
            <div class="badge badge-mustard" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">
                No exact match for "{{ $search }}" — showing AI-suggested items instead.
            </div>
        @endif

        <form method="GET" class="card-plain" style="padding:1rem; margin-bottom:1.5rem;">
            <div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:0.75rem;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="input-cc">
                <select name="category_id" class="input-cc">
                    <option value="">All categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) $categoryId === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->type }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; flex-wrap:wrap; align-items:end; gap:0.75rem;">
                <div>
                    <label style="display:block; font-size:0.7rem; color: var(--cc-text-muted); margin-bottom:0.2rem;">Min price</label>
                    <input type="number" name="min_price" value="{{ $minPrice }}" style="width:90px;" class="input-cc">
                </div>
                <div>
                    <label style="display:block; font-size:0.7rem; color: var(--cc-text-muted); margin-bottom:0.2rem;">Max price</label>
                    <input type="number" name="max_price" value="{{ $maxPrice }}" style="width:90px;" class="input-cc">
                </div>
                <div>
                    <label style="display:block; font-size:0.7rem; color: var(--cc-text-muted); margin-bottom:0.2rem;">Max spicy (food)</label>
                    <select name="spicy_level" style="width:90px;" class="input-cc">
                        <option value="">Any</option>
                        @for ($i = 0; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ (string) $spicyLevel === (string) $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.7rem; color: var(--cc-text-muted); margin-bottom:0.2rem;">Max calories</label>
                    <input type="number" name="max_calories" value="{{ $maxCalories }}" style="width:90px;" class="input-cc">
                </div>
                <button type="submit" class="btn-ink">Filter</button>
                <a href="{{ route('menu.index') }}" style="font-size:0.8125rem; color: var(--cc-text-muted); padding-bottom:0.6rem; text-decoration:none;">Reset</a>
            </div>
        </form>

        <h3 class="font-display" style="font-weight:700; margin-bottom:0.75rem; color: var(--cc-ink);">Food</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            @forelse ($foodItems as $item)
                <div class="card-ticket" style="padding:1rem; position:relative;">
                    <form action="{{ route('favorites.toggle') }}" method="POST" style="position:absolute; top:10px; right:10px;">
                        @csrf
                        <input type="hidden" name="type" value="food">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button type="submit" style="background:none; border:none; font-size:18px; cursor:pointer; line-height:1;">
                            {{ in_array($item->id, $favoriteFoodIds) ? '❤️' : '🤍' }}
                        </button>
                    </form>

                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"  alt="{{ $item->name }}">
                    @else
                        <div style="width:100%; height:120px; border-radius:8px; margin-bottom:0.6rem; background: var(--cc-paper); border:1px dashed var(--cc-line); display:flex; align-items:center; justify-content:center; font-size:2rem;">🍽️</div>
                    @endif
                    <a href="{{ route('menu.food.show', $item) }}" style="font-weight:700; color: var(--cc-ink); text-decoration:none; padding-right:1.5rem; display:block;">{{ $item->name }}</a>
                    <p style="font-size:0.75rem; color: var(--cc-text-muted); margin-top:0.2rem;">
                        {{ $item->category->name ?? '' }} · Spicy {{ $item->spicy_level }}/5 @if($item->calories) · {{ $item->calories }} cal @endif
                    </p>
                    @if ($item->reviews_avg_rating)
                        <p style="font-size:0.75rem; color: var(--cc-mustard-dark); margin-top:0.15rem;">{{ str_repeat('★', round($item->reviews_avg_rating)) }} {{ number_format($item->reviews_avg_rating, 1) }}</p>
                    @endif
                    <p class="font-mono" style="margin-top:0.5rem; font-weight:700;">{{ number_format($item->price, 2) }} EGP</p>

                    <form action="{{ route('cart.add') }}" method="POST" style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px dashed var(--cc-line);">
                        @csrf
                        <input type="hidden" name="type" value="food">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                            <label style="font-size:0.75rem; color: var(--cc-text-muted);">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" style="width:4rem;" class="input-cc">
                        </div>
                        <button type="submit" class="btn-mustard" style="width:100%; font-size:0.8125rem;">Add to cart</button>
                    </form>
                </div>
            @empty
                <p style="color: var(--cc-text-muted); grid-column:1 / -1;">No food items match these filters.</p>
            @endforelse
        </div>

        <h3 class="font-display" style="font-weight:700; margin-bottom:0.75rem; color: var(--cc-ink);">Beverages</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse ($beverages as $item)
                <div class="card-ticket" style="padding:1rem; position:relative;">
                    <form action="{{ route('favorites.toggle') }}" method="POST" style="position:absolute; top:10px; right:10px;">
                        @csrf
                        <input type="hidden" name="type" value="beverage">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button type="submit" style="background:none; border:none; font-size:18px; cursor:pointer; line-height:1;">
                            {{ in_array($item->id, $favoriteBeverageIds) ? '❤️' : '🤍' }}
                        </button>
                    </form>

                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    @else
                        <div style="width:100%; height:120px; border-radius:8px; margin-bottom:0.6rem; background: var(--cc-paper); border:1px dashed var(--cc-line); display:flex; align-items:center; justify-content:center; font-size:2rem;">🥤</div>
                    @endif
                    <a href="{{ route('menu.beverage.show', $item) }}" style="font-weight:700; color: var(--cc-ink); text-decoration:none; padding-right:1.5rem; display:block;">{{ $item->name }}</a>
                    <p style="font-size:0.75rem; color: var(--cc-text-muted); margin-top:0.2rem;">
                        {{ $item->category->name ?? '' }} @if($item->calories) · {{ $item->calories }} cal @endif
                    </p>
                    @if ($item->reviews_avg_rating)
                        <p style="font-size:0.75rem; color: var(--cc-mustard-dark); margin-top:0.15rem;">{{ str_repeat('★', round($item->reviews_avg_rating)) }} {{ number_format($item->reviews_avg_rating, 1) }}</p>
                    @endif
                    <p class="font-mono" style="margin-top:0.5rem; font-weight:700;">{{ number_format($item->price, 2) }} EGP</p>

                    <form action="{{ route('cart.add') }}" method="POST" style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px dashed var(--cc-line);">
                        @csrf
                        <input type="hidden" name="type" value="beverage">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                            <label style="font-size:0.75rem; color: var(--cc-text-muted);">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" style="width:4rem;" class="input-cc">
                        </div>
                        <button type="submit" class="btn-mustard" style="width:100%; font-size:0.8125rem;">Add to cart</button>
                    </form>
                </div>
            @empty
                <p style="color: var(--cc-text-muted); grid-column:1 / -1;">No beverages match these filters.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
