@php
    $isAdmin = auth()->user()->isAdmin();

    if ($isAdmin) {
        $customerCount = \App\Models\User::where('role', 'customer')->count();
        $ordersToday = \App\Models\Order::whereDate('created_at', today())->count();
        $lowStockCount = \App\Models\FoodItem::where('available_quantity', '<=', 5)->count()
            + \App\Models\Beverage::where('available_quantity', '<=', 5)->count();
    } else {
        $recentOrders = auth()->user()->orders()->latest()->take(3)->get();
        $favoriteCount = auth()->user()->favorites()->count();
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.5rem; font-weight:700; color: var(--cc-ink);">
            Welcome back, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if ($isAdmin)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('admin.users.index') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color: var(--cc-text-muted);">Customers</p>
                    <p class="font-mono" style="font-size:1.75rem; color: var(--cc-ink); margin-top:0.25rem;">{{ $customerCount }}</p>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color: var(--cc-text-muted);">Orders today</p>
                    <p class="font-mono" style="font-size:1.75rem; color: var(--cc-ink); margin-top:0.25rem;">{{ $ordersToday }}</p>
                </a>
                <a href="{{ route('admin.food-items.index') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color: var(--cc-text-muted);">Low stock items</p>
                    <p class="font-mono" style="font-size:1.75rem; color: var(--cc-clay); margin-top:0.25rem;">{{ $lowStockCount }}</p>
                </a>
            </div>

            <p style="font-size:0.875rem; color: var(--cc-text-muted);">
                See the full picture on the <a href="{{ route('admin.statistics.index') }}" style="color: var(--cc-mustard-dark); font-weight:600;">Statistics</a> page,
                or ask the <a href="{{ route('chatbot.index') }}" style="color: var(--cc-mustard-dark); font-weight:600;">assistant</a> directly.
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('menu.index') }}" class="btn-mustard" style="padding:1.25rem; justify-content:flex-start; text-align:left; flex-direction:column; align-items:flex-start; height:auto;">
                    <span style="font-family:'Baloo 2', sans-serif; font-size:1.1rem;">Browse the menu</span>
                    <span style="font-size:0.8rem; font-weight:500; opacity:0.8;">See what's cooking today</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color: var(--cc-text-muted);">Favorites</p>
                    <p class="font-mono" style="font-size:1.75rem; color: var(--cc-ink); margin-top:0.25rem;">{{ $favoriteCount }}</p>
                </a>
                <a href="{{ route('surprise-me') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.95rem; font-weight:700; color: var(--cc-ink);">🎲 Surprise Me</p>
                    <p style="font-size:0.8rem; color: var(--cc-text-muted); margin-top:0.25rem;">Not sure what to order?</p>
                </a>
            </div>

            <div class="card-plain" style="overflow:hidden;">
                <div style="padding: 0.9rem 1.25rem; border-bottom:1px solid var(--cc-line); font-weight:700; font-family:'Baloo 2',sans-serif;">
                    Recent orders
                </div>
                @forelse ($recentOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" style="display:flex; justify-content:space-between; padding:0.75rem 1.25rem; border-bottom:1px solid var(--cc-line); text-decoration:none; color: var(--cc-ink); font-size:0.875rem;">
                        <span>#{{ $order->id }} — {{ $order->created_at->format('M j') }}</span>
                        <span>
                            <span class="badge badge-mustard">{{ $order->status }}</span>
                            <span class="font-mono" style="margin-left:0.5rem;">{{ number_format($order->total_price, 2) }} EGP</span>
                        </span>
                    </a>
                @empty
                    <p style="padding: 1.25rem; color: var(--cc-text-muted); font-size:0.875rem;">No orders yet — head to the menu to place your first one.</p>
                @endforelse
            </div>
        @endif
    </div>
</x-app-layout>
