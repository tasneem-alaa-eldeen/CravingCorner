<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Orders</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <form method="GET" style="margin-bottom:1rem;">
            <select name="status" onchange="this.form.submit()" class="input-cc">
                <option value="">All statuses</option>
                @foreach (['pending', 'preparing', 'ready', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </form>

        <div class="card-plain" style="overflow:hidden;">
            <table style="width:100%; text-align:left; font-size:0.875rem; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--cc-line); text-transform:uppercase; font-size:0.7rem; color: var(--cc-text-muted);">
                        <th style="padding:0.75rem 1.1rem;">Order</th>
                        <th style="padding:0.75rem 1.1rem;">Customer</th>
                        <th style="padding:0.75rem 1.1rem;">Total</th>
                        <th style="padding:0.75rem 1.1rem;">Status</th>
                        <th style="padding:0.75rem 1.1rem;">Payment</th>
                        <th style="padding:0.75rem 1.1rem; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr style="border-bottom:1px solid var(--cc-line);">
                            <td class="font-mono" style="padding:0.75rem 1.1rem;">#{{ $order->id }}</td>
                            <td style="padding:0.75rem 1.1rem;">{{ $order->user->name ?? '—' }}</td>
                            <td class="font-mono" style="padding:0.75rem 1.1rem;">{{ number_format($order->total_price, 2) }} EGP</td>
                            <td style="padding:0.75rem 1.1rem;">
                                <span class="badge {{ match(true) { $order->status === 'completed' => 'badge-sage', $order->status === 'cancelled' => 'badge-clay', default => 'badge-mustard' } }}">{{ $order->status }}</span>
                            </td>
                            <td style="padding:0.75rem 1.1rem;"><span class="badge badge-muted">{{ $order->payment_status }}</span></td>
                            <td style="padding:0.75rem 1.1rem; text-align:right;">
                                <a href="{{ route('admin.orders.show', $order) }}" style="color: var(--cc-mustard-dark); font-weight:600; text-decoration:none;">View / Update</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="padding:2rem; text-align:center; color: var(--cc-text-muted);">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
