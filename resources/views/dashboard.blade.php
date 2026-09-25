@php
    use Illuminate\Support\Carbon;

    $isAdmin = auth()->user()->isAdmin();

    if ($isAdmin) {
        $customerCount = \App\Models\User::where('role', 'customer')->count();
        $ordersToday = \App\Models\Order::whereDate('created_at', today())->count();
        $lowStockCount = \App\Models\FoodItem::where('available_quantity', '<=', 5)->count()
            + \App\Models\Beverage::where('available_quantity', '<=', 5)->count();

        // Orders per day, last 7 days — small trend chart on the admin home.
        $ordersByDay = \App\Models\Order::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
        $ordersTrend = collect(range(6, 0))->map(function ($daysAgo) use ($ordersByDay) {
            $date = Carbon::today()->subDays($daysAgo);
            return ['label' => $date->format('D'), 'total' => (int) ($ordersByDay[$date->toDateString()] ?? 0)];
        });
    } else {
        $recentOrders = auth()->user()->orders()->latest()->take(3)->get();
        $favoriteCount = auth()->user()->favorites()->count();

        // This customer's spending over the last 6 months.
        $spendByMonth = auth()->user()->orders()
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total_price) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');
        $spendingTrend = collect(range(5, 0))->map(function ($monthsAgo) use ($spendByMonth) {
            $month = Carbon::now()->subMonths($monthsAgo);
            return ['label' => $month->format('M'), 'total' => (float) ($spendByMonth[$month->format('Y-m')] ?? 0)];
        });

        // Spend split by category (food vs beverage categories), from order history.
        $categorySpend = \App\Models\OrderItem::whereHas('order', fn ($q) => $q->where('user_id', auth()->id()))
            ->get(['itemable_type', 'itemable_id', 'subtotal'])
            ->map(function ($row) {
                $item = $row->itemable_type::with('category')->find($row->itemable_id);
                return ['category' => $item->category->name ?? 'Other', 'subtotal' => (float) $row->subtotal];
            })
            ->groupBy('category')
            ->map(fn ($rows) => $rows->sum('subtotal'))
            ->sortDesc()
            ->take(6);
    }

    $categories = \App\Models\Category::withCount(['foodItems', 'beverages'])->get();
@endphp

<x-app-layout>
    <div class="py-8" style="max-width:80rem; margin:0 auto; padding-left:1.5rem; padding-right:1.5rem;">

        <!-- Hero -->
        <div class="cc-hero">
            <div class="cc-hero-badge">
                <x-application-logo style="width:2.1rem; height:2.1rem;" />
            </div>
            <div>
                <p class="cc-hero-title">Welcome back, {{ auth()->user()->name }} 👋</p>
                <p class="cc-hero-sub">
                    @if ($isAdmin)
                        Here's what's happening at CravingCorner today.
                    @else
                        What are you craving today?
                    @endif
                </p>
            </div>
        </div>

        @if ($isAdmin)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 cc-animate-stagger">
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

            <div class="card-plain" style="padding:1.1rem; margin-bottom:2rem;">
                <p style="font-weight:700; font-family:'Baloo 2',sans-serif; margin-bottom:0.6rem;">Orders — last 7 days</p>
                <canvas id="dashOrdersTrend" height="90"></canvas>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 cc-animate-stagger">
                <a href="{{ route('menu.index') }}" class="btn-mustard" style="padding:1.25rem; justify-content:flex-start; text-align:left; flex-direction:column; align-items:flex-start; height:auto;">
                    <span style="font-family:'Baloo 2', sans-serif; font-size:1.1rem;">Browse the menu</span>
                    <span style="font-size:0.8rem; font-weight:500; opacity:0.8;">See what's cooking today</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color: var(--cc-text-muted);">Favorites</p>
                    <p class="font-mono" style="font-size:1.75rem; color: var(--cc-ink); margin-top:0.25rem;">{{ $favoriteCount }}</p>
                </a>
                <a href="{{ route('recommendations.index') }}" class="card-ticket" style="padding:1.25rem; text-decoration:none; display:block;">
                    <p style="font-size:0.95rem; font-weight:700; color: var(--cc-ink);">✨ Recommended for you</p>
                    <p style="font-size:0.8rem; color: var(--cc-text-muted); margin-top:0.25rem;">See your top matches</p>
                </a>
            </div>

            <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:1.5rem; margin-bottom:2rem;">
                <div class="card-plain" style="padding:1.1rem;">
                    <p style="font-weight:700; font-family:'Baloo 2',sans-serif; margin-bottom:0.6rem;">Your spending — last 6 months</p>
                    <canvas id="dashSpendingTrend" height="120"></canvas>
                </div>
                <div class="card-plain" style="padding:1.1rem;">
                    <p style="font-weight:700; font-family:'Baloo 2',sans-serif; margin-bottom:0.6rem;">Where it goes</p>
                    <canvas id="dashCategorySpend" height="120"></canvas>
                </div>
            </div>
        @endif

        <!-- Category cards: full-bleed image + dark overlay + hover zoom -->
        <h3 class="font-display" style="font-weight:700; margin-bottom:0.75rem; color: var(--cc-ink);">
            {{ $isAdmin ? 'Categories' : 'Browse by category' }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8 cc-animate-stagger">
            @foreach ($categories as $cat)
                @php
                    $count = $cat->type === 'food' ? $cat->food_items_count : $cat->beverages_count;
                    $link = $isAdmin
                        ? ($cat->type === 'food' ? route('admin.food-items.index') : route('admin.beverages.index'))
                        : route('menu.index', ['category_id' => $cat->id]);
                @endphp
                <a href="{{ $link }}" class="cc-cover-card">
                    @if ($cat->image)
                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                    @else
                        <div class="cc-cover-fallback">{{ $cat->type === 'food' ? '🍽️' : '🥤' }}</div>
                    @endif
                    <div class="cc-cover-overlay"></div>
                    <div class="cc-cover-content">
                        <p class="cc-cover-title">{{ $cat->name }}</p>
                        <p class="cc-cover-meta">{{ $count }} items · {{ ucfirst($cat->type) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($isAdmin)
            <p style="font-size:0.875rem; color: var(--cc-text-muted);">
                See the full picture on the <a href="{{ route('admin.statistics.index') }}" style="color: var(--cc-mustard-dark); font-weight:600;">Statistics</a> page,
                or ask the assistant (bottom-right) directly.
            </p>
        @else
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            const ink = cssVar('--cc-ink') || '#1F3B2C';
            const mustard = cssVar('--cc-mustard') || '#E2A63B';
            const sage = cssVar('--cc-sage') || '#6B9080';
            const clay = cssVar('--cc-clay') || '#C1443B';
            const line = cssVar('--cc-line') || '#D8CFBA';

            @if ($isAdmin)
                new Chart(document.getElementById('dashOrdersTrend'), {
                    type: 'line',
                    data: {
                        labels: @json($ordersTrend->pluck('label')),
                        datasets: [{
                            label: 'Orders',
                            data: @json($ordersTrend->pluck('total')),
                            borderColor: ink,
                            backgroundColor: ink + '22',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                        }],
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: line } }, x: { grid: { display: false } } },
                    },
                });
            @else
                new Chart(document.getElementById('dashSpendingTrend'), {
                    type: 'line',
                    data: {
                        labels: @json($spendingTrend->pluck('label')),
                        datasets: [{
                            label: 'EGP spent',
                            data: @json($spendingTrend->pluck('total')),
                            borderColor: mustard,
                            backgroundColor: mustard + '33',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                        }],
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: line } }, x: { grid: { display: false } } },
                    },
                });

                new Chart(document.getElementById('dashCategorySpend'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($categorySpend->keys()),
                        datasets: [{
                            data: @json($categorySpend->values()),
                            backgroundColor: [mustard, sage, clay, '#C98A22', ink, '#7A7566'],
                            borderWidth: 0,
                        }],
                    },
                    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } },
                });
            @endif
        });
    </script>
</x-app-layout>
