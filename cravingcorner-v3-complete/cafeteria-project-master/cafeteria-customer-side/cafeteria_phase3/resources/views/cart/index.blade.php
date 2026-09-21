<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your Cart</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @forelse ($cart as $line)
                <div class="flex items-center justify-between px-5 py-3 border-b last:border-0">
                    <div>
                        <p class="text-sm text-gray-800">{{ $line['item']->name }}</p>
                        <p class="text-xs text-gray-500">{{ $line['quantity'] }} &times; {{ number_format($line['item']->price, 2) }} EGP</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <p class="text-sm font-medium">{{ number_format($line['subtotal'], 2) }} EGP</p>
                        <form action="{{ route('cart.remove', $line['key']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-600">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-400">Your cart is empty.</p>
            @endforelse
        </div>

        @if (! empty($cart))
            <div class="mt-4 flex items-center justify-between">
                <p class="font-semibold">Total: {{ number_format(collect($cart)->sum('subtotal'), 2) }} EGP</p>
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <x-primary-button>Place order</x-primary-button>
                </form>
            </div>
        @endif

        <a href="{{ route('menu.index') }}" class="inline-block mt-6 text-sm text-gray-500">&larr; Back to menu</a>
    </div>
</x-app-layout>
