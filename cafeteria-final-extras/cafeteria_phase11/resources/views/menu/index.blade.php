<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Menu</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        @if ($aiSearchUsed ?? false)
            <div class="mb-4 rounded-md p-3 text-sm" style="background:#eef2ff; color:#4338ca;">
                No exact match for "{{ $search }}" — showing AI-suggested items instead.
            </div>
        @endif

        <form method="GET" class="bg-white shadow-sm rounded-lg p-4 mb-6">
            <div class="flex flex-wrap gap-3 mb-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="border-gray-300 rounded-md text-sm">
                <select name="category_id" class="border-gray-300 rounded-md text-sm">
                    <option value="">All categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) $categoryId === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->type }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap items-end gap-3 mb-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Min price</label>
                    <input type="number" name="min_price" value="{{ $minPrice }}" style="width:100px;" class="border-gray-300 rounded-md text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Max price</label>
                    <input type="number" name="max_price" value="{{ $maxPrice }}" style="width:100px;" class="border-gray-300 rounded-md text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Max spicy level (food)</label>
                    <select name="spicy_level" style="width:100px;" class="border-gray-300 rounded-md text-sm">
                        <option value="">Any</option>
                        @for ($i = 0; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ (string) $spicyLevel === (string) $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Max calories</label>
                    <input type="number" name="max_calories" value="{{ $maxCalories }}" style="width:100px;" class="border-gray-300 rounded-md text-sm">
                </div>
                <button type="submit" style="background-color:#1f2937; color:#fff; padding:8px 16px; border-radius:6px; border:none; font-size:14px; cursor:pointer;">
                    Filter
                </button>
                <a href="{{ route('menu.index') }}" style="font-size:13px; color:#6b7280; padding-bottom:8px;">Reset</a>
            </div>
        </form>

        <h3 class="font-semibold text-gray-700 mb-3">Food</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            @forelse ($foodItems as $item)
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col relative">
                    <form action="{{ route('favorites.toggle') }}" method="POST" style="position:absolute; top:12px; right:12px;">
                        @csrf
                        <input type="hidden" name="type" value="food">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button type="submit" style="background:none; border:none; font-size:18px; cursor:pointer; line-height:1;">
                            {{ in_array($item->id, $favoriteFoodIds) ? '❤️' : '🤍' }}
                        </button>
                    </form>

                    <a href="{{ route('menu.food.show', $item) }}" class="font-medium text-gray-800 hover:underline pr-6">{{ $item->name }}</a>
                    <p class="text-xs text-gray-500 mt-1">{{ $item->category->name ?? '' }} · Spicy {{ $item->spicy_level }}/5 @if($item->calories) · {{ $item->calories }} cal @endif</p>
                    <p class="mt-2 text-sm font-semibold">{{ number_format($item->price, 2) }} EGP</p>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3 pt-3 border-t border-gray-100">
                        @csrf
                        <input type="hidden" name="type" value="food">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="flex items-center gap-2 mb-2">
                            <label class="text-xs text-gray-500">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" style="width: 4rem;" class="border-gray-300 rounded-md text-sm">
                        </div>
                        <button type="submit" style="display:block; width:100%; background-color:#4f46e5; color:#ffffff; padding:8px; border-radius:6px; font-size:12px; font-weight:600; border:none; cursor:pointer;">
                            Add to cart
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400 col-span-full">No food items match these filters.</p>
            @endforelse
        </div>

        <h3 class="font-semibold text-gray-700 mb-3">Beverages</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse ($beverages as $item)
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col relative">
                    <form action="{{ route('favorites.toggle') }}" method="POST" style="position:absolute; top:12px; right:12px;">
                        @csrf
                        <input type="hidden" name="type" value="beverage">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button type="submit" style="background:none; border:none; font-size:18px; cursor:pointer; line-height:1;">
                            {{ in_array($item->id, $favoriteBeverageIds) ? '❤️' : '🤍' }}
                        </button>
                    </form>

                    <a href="{{ route('menu.beverage.show', $item) }}" class="font-medium text-gray-800 hover:underline pr-6">{{ $item->name }}</a>
                    <p class="text-xs text-gray-500 mt-1">{{ $item->category->name ?? '' }} @if($item->calories) · {{ $item->calories }} cal @endif</p>
                    <p class="mt-2 text-sm font-semibold">{{ number_format($item->price, 2) }} EGP</p>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3 pt-3 border-t border-gray-100">
                        @csrf
                        <input type="hidden" name="type" value="beverage">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="flex items-center gap-2 mb-2">
                            <label class="text-xs text-gray-500">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" style="width: 4rem;" class="border-gray-300 rounded-md text-sm">
                        </div>
                        <button type="submit" style="display:block; width:100%; background-color:#4f46e5; color:#ffffff; padding:8px; border-radius:6px; font-size:12px; font-weight:600; border:none; cursor:pointer;">
                            Add to cart
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400 col-span-full">No beverages match these filters.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
