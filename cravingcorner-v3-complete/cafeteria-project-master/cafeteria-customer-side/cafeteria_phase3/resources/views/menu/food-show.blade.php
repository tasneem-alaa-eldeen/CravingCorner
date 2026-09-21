<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $foodItem->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <p class="text-sm text-gray-500">{{ $foodItem->category->name ?? '' }}</p>
            <p class="mt-2 text-gray-700">{{ $foodItem->description }}</p>

            <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-400">Price</dt><dd>{{ number_format($foodItem->price, 2) }} EGP</dd></div>
                <div><dt class="text-gray-400">Calories</dt><dd>{{ $foodItem->calories ?? '—' }}</dd></div>
                <div><dt class="text-gray-400">Spicy level</dt><dd>{{ $foodItem->spicy_level }}/5</dd></div>
                <div><dt class="text-gray-400">Prep time</dt><dd>{{ $foodItem->preparation_time ?? '—' }} min</dd></div>
            </dl>

            @if ($foodItem->ingredients)
                <p class="mt-4 text-sm"><span class="text-gray-400">Ingredients:</span> {{ $foodItem->ingredients }}</p>
            @endif

            <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-center gap-3">
                @csrf
                <input type="hidden" name="type" value="food">
                <input type="hidden" name="id" value="{{ $foodItem->id }}">
                <input type="number" name="quantity" value="1" min="1" class="w-20 border-gray-300 rounded-md text-sm">
                <x-primary-button>Add to cart</x-primary-button>
            </form>

            <a href="{{ route('menu.index') }}" class="inline-block mt-6 text-sm text-gray-500">&larr; Back to menu</a>
        </div>
    </div>
</x-app-layout>
