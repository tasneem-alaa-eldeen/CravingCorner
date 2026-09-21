<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="card-ticket" style="padding:1.5rem;">
            <p style="font-size:0.8rem; color: var(--cc-text-muted); margin-bottom:0.25rem;">Customer</p>
            <p style="margin-bottom:1.25rem;">{{ $order->user->name ?? '—' }} ({{ $order->user->email ?? '' }})</p>

            <div style="border-top:1px dashed var(--cc-line); border-bottom:1px dashed var(--cc-line);">
                @foreach ($order->items as $line)
                    <div style="display:flex; justify-content:space-between; padding:0.6rem 0; font-size:0.875rem;">
                        <span>{{ $line->itemable->name ?? 'Item removed' }} &times; {{ $line->quantity }}</span>
                        <span class="font-mono">{{ number_format($line->subtotal, 2) }} EGP</span>
                    </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; font-weight:700; padding:1rem 0 1.5rem;">
                <span>Total</span>
                <span class="font-mono">{{ number_format($order->total_price, 2) }} EGP</span>
            </div>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:flex; flex-direction:column; gap:1rem;">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="status" value="Order status" />
                    <select name="status" id="status" class="input-cc" style="width:100%;">
                        @foreach (['pending', 'preparing', 'ready', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="payment_status" value="Payment status" />
                    <select name="payment_status" id="payment_status" class="input-cc" style="width:100%;">
                        @foreach (['pending', 'paid', 'failed'] as $status)
                            <option value="{{ $status }}" {{ $order->payment_status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <x-primary-button style="width:100%;">Update order</x-primary-button>
            </form>
        </div>

        <a href="{{ route('admin.orders.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to orders</a>
    </div>
</x-app-layout>
