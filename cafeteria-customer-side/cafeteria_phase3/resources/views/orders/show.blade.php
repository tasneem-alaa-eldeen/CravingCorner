<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex justify-between text-sm mb-4">
                <span class="capitalize">Status: <strong>{{ $order->status }}</strong></span>
                <span class="capitalize">Payment: <strong>{{ $order->payment_status }}</strong></span>
            </div>

            <div class="divide-y">
                @foreach ($order->items as $line)
                    <div class="flex items-center justify-between py-2 text-sm">
                        <span>{{ $line->itemable->name ?? 'Item removed' }} &times; {{ $line->quantity }}</span>
                        <span>{{ number_format($line->subtotal, 2) }} EGP</span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between mt-4 font-semibold">
                <span>Total</span>
                <span>{{ number_format($order->total_price, 2) }} EGP</span>
            </div>

            @if ($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-6" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <button class="text-sm text-red-600">Cancel order</button>
                </form>
            @endif
        </div>

        <a href="{{ route('orders.index') }}" class="inline-block mt-6 text-sm text-gray-500">&larr; Back to my orders</a>
    </div>
</x-app-layout>
