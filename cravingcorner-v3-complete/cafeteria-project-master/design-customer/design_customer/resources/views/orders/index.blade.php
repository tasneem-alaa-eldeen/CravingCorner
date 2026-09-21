<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">My Orders</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="card-plain" style="overflow:hidden;">
            @forelse ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" style="display:flex; align-items:center; justify-content:space-between; padding:0.9rem 1.25rem; border-bottom:1px solid var(--cc-line); text-decoration:none; color: var(--cc-ink);">
                    <div>
                        <p style="font-size:0.9rem;">Order #{{ $order->id }}</p>
                        <p style="font-size:0.75rem; color: var(--cc-text-muted);">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div style="text-align:right;">
                        <p class="font-mono" style="font-weight:600;">{{ number_format($order->total_price, 2) }} EGP</p>
                        <span class="badge {{ match(true) { $order->status === 'completed' => 'badge-sage', $order->status === 'cancelled' => 'badge-clay', default => 'badge-mustard' } }}">{{ $order->status }}</span>
                    </div>
                </a>
            @empty
                <p style="padding:2rem; text-align:center; color: var(--cc-text-muted); font-size:0.875rem;">You haven't placed any orders yet.</p>
            @endforelse
        </div>

        <a href="{{ route('menu.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to menu</a>
    </div>
</x-app-layout>
