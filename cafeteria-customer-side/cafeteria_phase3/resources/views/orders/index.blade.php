<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Orders</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @forelse ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between px-5 py-3 border-b last:border-0 hover:bg-gray-50">
                    <div>
                        <p class="text-sm text-gray-800">Order #{{ $order->id }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ number_format($order->total_price, 2) }} EGP</p>
                        <span class="text-xs capitalize text-gray-500">{{ $order->status }}</span>
                    </div>
                </a>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-400">You haven't placed any orders yet.</p>
            @endforelse
        </div>

        <a href="{{ route('menu.index') }}" class="inline-block mt-6 text-sm text-gray-500">&larr; Back to menu</a>
    </div>
</x-app-layout>
