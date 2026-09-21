<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Menu</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3 mb-6">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="border-gray-300 rounded-md text-sm">
            <select name="category_id" class="border-gray-300 rounded-md text-sm">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (string) $categoryId === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->type }})</option>
                @endforeach
            </select>
            <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filter</button>
        </form>

        <h3 class="font-semibold text-gray-700 mb-3">Food</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            @forelse ($foodItems as $item)
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <a href="{{ route('menu.food.show', $item) }}" class="font-medium text-gray-800 hover:underline">{{ $item->name }}</a>
                    <p class="text-xs text-gray-500 mt-1">{{ $item->category->name ?? '' }}</p>
                    <p class="mt-2 text-sm font-semibold">{{ number_format($item->price, 2) }} EGP</p>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3 pt-3 border-t border-gray-100">
                        @csrf
                        <input type="hidden" name="type" value="food">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="flex items-center gap-2 mb-2">
                            <label class="text-xs text-gray-500">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" style="width: 4rem;" class="border-gray-300 rounded-md text-sm">
                        </div>
                        <button type="submit" style="display:block; width:100%;" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700">
                            Add to cart
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400 col-span-full">No food items found.</p>
            @endforelse
        </div>

        <h3 class="font-semibold text-gray-700 mb-3">Beverages</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse ($beverages as $item)
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <a href="{{ route('menu.beverage.show', $item) }}" class="font-medium text-gray-800 hover:underline">{{ $item->name }}</a>
                    <p class="text-xs text-gray-500 mt-1">{{ $item->category->name ?? '' }}</p>
                    <p class="mt-2 text-sm font-semibold">{{ number_format($item->price, 2) }} EGP</p>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3 pt-3 border-t border-gray-100">
                        @csrf
                        <input type="hidden" name="type" value="beverage">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="flex items-center gap-2 mb-2">
                            <label class="text-xs text-gray-500">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" style="width: 4rem;" class="border-gray-300 rounded-md text-sm">
                        </div>
                        <button type="submit" style="display:block; width:100%;" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700">
                            Add to cart
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400 col-span-full">No beverages found.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
