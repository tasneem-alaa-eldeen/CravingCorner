<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="card-ticket" style="padding:1.5rem;">
            <div style="display:flex; justify-content:space-between; margin-bottom:1rem;">
                <span class="badge {{ match(true) { $order->status === 'completed' => 'badge-sage', $order->status === 'cancelled' => 'badge-clay', default => 'badge-mustard' } }}">{{ $order->status }}</span>
                <span class="badge badge-muted">{{ $order->payment_status }}</span>
            </div>

            <div style="border-top:1px dashed var(--cc-line);">
                @foreach ($order->items as $line)
                    <div style="display:flex; justify-content:space-between; padding:0.6rem 0; border-bottom:1px dashed var(--cc-line); font-size:0.875rem;">
                        <span>{{ $line->itemable->name ?? 'Item removed' }} &times; {{ $line->quantity }}</span>
                        <span class="font-mono">{{ number_format($line->subtotal, 2) }} EGP</span>
                    </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:1rem; font-weight:700;">
                <span>Total</span>
                <span class="font-mono">{{ number_format($order->total_price, 2) }} EGP</span>
            </div>

            @if ($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" style="margin-top:1.25rem;" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <button type="submit" class="btn-text-clay">Cancel order</button>
                </form>
            @endif
        </div>

        <a href="{{ route('orders.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to my orders</a>
    </div>
</x-app-layout>
