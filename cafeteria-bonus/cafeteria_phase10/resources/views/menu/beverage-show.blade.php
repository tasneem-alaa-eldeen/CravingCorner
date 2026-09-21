<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $beverage->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <p class="text-sm text-gray-500">{{ $beverage->category->name ?? '' }}</p>
            @if ($avgRating)
                <p class="text-sm text-yellow-500">{{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5 - round($avgRating)) }}
                    <span class="text-gray-500">({{ number_format($avgRating, 1) }} / 5, {{ $beverage->reviews->count() }} reviews)</span>
                </p>
            @else
                <p class="text-sm text-gray-400">No reviews yet.</p>
            @endif

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
                <input type="number" name="quantity" value="1" min="1" style="width:80px;" class="border-gray-300 rounded-md text-sm">
                <button type="submit" style="background-color:#4f46e5; color:#fff; padding:8px 16px; border-radius:6px; border:none; font-weight:600; cursor:pointer;">Add to cart</button>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6 mt-6">
            <h3 class="font-semibold text-gray-700 mb-3">Leave a review</h3>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="beverage">
                <input type="hidden" name="id" value="{{ $beverage->id }}">

                <label class="block text-sm text-gray-500 mb-1">Rating</label>
                <select name="rating" required style="width:100px;" class="border-gray-300 rounded-md text-sm mb-3">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} ★</option>
                    @endfor
                </select>

                <label class="block text-sm text-gray-500 mb-1">Comment (optional)</label>
                <textarea name="comment" rows="2" style="width:100%;" class="border-gray-300 rounded-md text-sm mb-3"></textarea>

                <button type="submit" style="background-color:#1f2937; color:#fff; padding:8px 16px; border-radius:6px; border:none; font-size:14px; cursor:pointer;">
                    Submit review
                </button>
            </form>

            @if ($beverage->reviews->isNotEmpty())
                <div class="mt-6 divide-y">
                    @foreach ($beverage->reviews as $review)
                        <div class="py-3 text-sm">
                            <p class="font-medium">{{ $review->user->name }} — {{ str_repeat('★', $review->rating) }}</p>
                            @if ($review->comment)
                                <p class="text-gray-600 mt-1">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <a href="{{ route('menu.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:14px; color:#6b7280;">&larr; Back to menu</a>
    </div>
</x-app-layout>
