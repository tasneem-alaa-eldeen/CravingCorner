<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $beverage->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <p class="text-sm text-gray-500">{{ $beverage->category->name ?? '' }}</p>
            <p class="mt-2 text-gray-700">{{ $beverage->description }}</p>

            <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-400">Price</dt><dd>{{ number_format($beverage->price, 2) }} EGP</dd></div>
                <div><dt class="text-gray-400">Calories</dt><dd>{{ $beverage->calories ?? '—' }}</dd></div>
                <div><dt class="text-gray-400">Temperature</dt><dd>{{ ucfirst($beverage->temperature) }}</dd></div>
                <div><dt class="text-gray-400">Size</dt><dd>{{ $beverage->size ?? '—' }}</dd></div>
            </dl>

            @if ($beverage->ingredients)
                <p class="mt-4 text-sm"><span class="text-gray-400">Ingredients:</span> {{ $beverage->ingredients }}</p>
            @endif

            <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-center gap-3">
                @csrf
                <input type="hidden" name="type" value="beverage">
                <input type="hidden" name="id" value="{{ $beverage->id }}">
                <input type="number" name="quantity" value="1" min="1" class="w-20 border-gray-300 rounded-md text-sm">
                <x-primary-button>Add to cart</x-primary-button>
            </form>

            <a href="{{ route('menu.index') }}" class="inline-block mt-6 text-sm text-gray-500">&larr; Back to menu</a>
        </div>
    </div>
</x-app-layout>
