<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Menu</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">

        <!-- Hero banner -->
        <div class="cc-hero-banner" style="padding:1.9rem 2rem;">
            <p class="cc-hero-title" style="font-size:1.6rem;">What are you craving today? 🍽️</p>
            <p class="cc-hero-sub">Browse the full menu, or narrow it down with the filters below.</p>
        </div>

        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        @if ($aiSearchUsed ?? false)
            <div class="badge badge-mustard" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">
                No exact match for "{{ $search }}" — showing AI-suggested items instead.
            </div>
        @endif

        <!-- Filters -->
        <form method="GET" class="card-plain" style="padding:1.25rem; margin-bottom:2rem;">
            <div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:0.75rem;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search the menu..." class="input-cc" style="flex:1; min-width:200px;">
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
                <button type="submit" class="btn-mustard">Filter</button>
                <a href="{{ route('menu.index') }}" class="btn-outline">Reset</a>
            </div>
        </form>

        <h3 class="font-display" style="font-weight:700; margin-bottom:0.9rem; color: var(--cc-ink); font-size:1.15rem;">🍽️ Food</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-10 cc-animate-stagger">
            @forelse ($foodItems as $item)
                <div class="cc-food-card">
                    <div class="cc-food-media">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="cc-food-img" alt="{{ $item->name }}">
                        @else
                            <div class="cc-food-fallback">🍽️</div>
                        @endif
                        <div class="cc-food-heart">
                            <form action="{{ route('favorites.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="food">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit">{{ in_array($item->id, $favoriteFoodIds) ? '❤️' : '🤍' }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="cc-food-body">
                        <a href="{{ route('menu.food.show', $item) }}" class="cc-food-name">{{ $item->name }}</a>
                        <p class="cc-food-meta">
                            {{ $item->category->name ?? '' }} · Spicy {{ $item->spicy_level }}/5 @if($item->calories) · {{ $item->calories }} cal @endif
                        </p>
                        @if ($item->reviews_avg_rating)
                            <p class="cc-food-rate">{{ str_repeat('★', round($item->reviews_avg_rating)) }} {{ number_format($item->reviews_avg_rating, 1) }}</p>
                        @endif

                        <div class="cc-food-row">
                            <p class="cc-food-price">{{ number_format($item->price, 2) }} EGP</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" class="cc-food-add">
                            @csrf
                            <input type="hidden" name="type" value="food">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="input-cc">
                            <button type="submit" class="btn-mustard">Add to cart</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color: var(--cc-text-muted); grid-column:1 / -1;">No food items match these filters.</p>
            @endforelse
        </div>

        <h3 class="font-display" style="font-weight:700; margin-bottom:0.9rem; color: var(--cc-ink); font-size:1.15rem;">🥤 Beverages</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 cc-animate-stagger">
            @forelse ($beverages as $item)
                <div class="cc-food-card">
                    <div class="cc-food-media">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="cc-food-img" alt="{{ $item->name }}">
                        @else
                            <div class="cc-food-fallback">🥤</div>
                        @endif
                        <div class="cc-food-heart">
                            <form action="{{ route('favorites.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="beverage">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit">{{ in_array($item->id, $favoriteBeverageIds) ? '❤️' : '🤍' }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="cc-food-body">
                        <a href="{{ route('menu.beverage.show', $item) }}" class="cc-food-name">{{ $item->name }}</a>
                        <p class="cc-food-meta">
                            {{ $item->category->name ?? '' }} @if($item->calories) · {{ $item->calories }} cal @endif
                        </p>
                        @if ($item->reviews_avg_rating)
                            <p class="cc-food-rate">{{ str_repeat('★', round($item->reviews_avg_rating)) }} {{ number_format($item->reviews_avg_rating, 1) }}</p>
                        @endif

                        <div class="cc-food-row">
                            <p class="cc-food-price">{{ number_format($item->price, 2) }} EGP</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" class="cc-food-add">
                            @csrf
                            <input type="hidden" name="type" value="beverage">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="input-cc">
                            <button type="submit" class="btn-mustard">Add to cart</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color: var(--cc-text-muted); grid-column:1 / -1;">No beverages match these filters.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
