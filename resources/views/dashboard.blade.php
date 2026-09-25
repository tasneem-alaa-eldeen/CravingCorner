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
        $recentOrders = auth()->user()->orders()->latest()->take(4)->get();
        $favoriteCount = auth()->user()->favorites()->count();
        $cartCount = collect(session('cart', []))->sum('quantity');

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

    $orderStatusIcon = fn ($status) => match ($status) {
        'pending' => '🕓', 'preparing' => '👩‍🍳', 'ready' => '📦',
        'delivered', 'completed' => '✅', 'cancelled' => '✕', default => '🧾',
    };
@endphp

<x-app-layout>
    <div class="py-8" style="max-width:80rem; margin:0 auto; padding-left:1.5rem; padding-right:1.5rem;">

        @if ($isAdmin)
            {{-- ===================== ADMIN HERO ===================== --}}
            <div class="cc-hero-xl">
                <div>
                    <span class="cc-hero-xl-eyebrow">Admin dashboard</span>
                    <h1 class="cc-hero-xl-title">Welcome back, {{ auth()->user()->name }} 👋</h1>
                    <p class="cc-hero-xl-sub">Here's what's happening at CravingCorner today.</p>
                    <div class="cc-hero-xl-actions">
                        <a href="{{ route('admin.statistics.index') }}" class="btn-mustard">View full statistics</a>
                        <a href="{{ route('admin.orders.index') }}" class="btn-outline" style="background:rgba(255,255,255,0.08); color:#FBF3E4; border-color:rgba(255,255,255,0.2);">Manage orders</a>
                    </div>
                </div>
                <div class="cc-hero-xl-stats">
                    <div class="cc-stat-pill">
                        <span class="cc-stat-num">{{ $customerCount }}</span>
                        <span class="cc-stat-lbl">Customers</span>
                    </div>
                    <div class="cc-stat-pill">
                        <span class="cc-stat-num">{{ $ordersToday }}</span>
                        <span class="cc-stat-lbl">Orders today</span>
                    </div>
                    <div class="cc-stat-pill">
                        <span class="cc-stat-num">{{ $lowStockCount }}</span>
                        <span class="cc-stat-lbl">Low stock</span>
                    </div>
                </div>
            </div>

            {{-- ===================== ADMIN BENTO ROW ===================== --}}
            <div class="grid grid-cols-1 md:grid-cols-[1.7fr_1fr] gap-5 mb-10">
                <div class="card-plain" style="padding:1.5rem;">
                    <div class="cc-chart-head">
                        <div class="cc-icon-badge cc-icon-badge-mustard">📈</div>
                        <div>
                            <h4>Orders — last 7 days</h4>
                            <p>Daily order volume across the whole platform</p>
                        </div>
                    </div>
                    <canvas id="dashOrdersTrend" height="90"></canvas>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    <a href="{{ route('admin.food-items.index') }}" class="card-plain cc-stat-tile">
                        <div class="cc-icon-badge cc-icon-badge-clay">⚠️</div>
                        <div>
                            <p class="cc-stat-tile-num" style="color:var(--cc-clay);">{{ $lowStockCount }}</p>
                            <p class="cc-stat-tile-lbl">Items running low</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="card-plain cc-stat-tile">
                        <div class="cc-icon-badge cc-icon-badge-sage">👥</div>
                        <div>
                            <p class="cc-stat-tile-num">{{ $customerCount }}</p>
                            <p class="cc-stat-tile-lbl">Registered customers</p>
                        </div>
                    </a>
                </div>
            </div>
        @else
            {{-- ===================== CUSTOMER HERO ===================== --}}
            <div class="cc-hero-xl">
                <div>
                    <span class="cc-hero-xl-eyebrow">Home</span>
                    <h1 class="cc-hero-xl-title">Welcome back, {{ auth()->user()->name }} 👋</h1>
                    <p class="cc-hero-xl-sub">What are you craving today? Browse the menu or let us surprise you.</p>
                    <div class="cc-hero-xl-actions">
                        <a href="{{ route('menu.index') }}" class="btn-mustard">Browse the menu</a>
                        <a href="{{ route('surprise-me') }}" class="btn-outline" style="background:rgba(255,255,255,0.08); color:#FBF3E4; border-color:rgba(255,255,255,0.2);">🎲 Surprise me</a>
                    </div>
                </div>
                <div class="cc-hero-xl-stats">
                    <div class="cc-stat-pill">
                        <span class="cc-stat-num">{{ $favoriteCount }}</span>
                        <span class="cc-stat-lbl">Favorites</span>
                    </div>
                    <div class="cc-stat-pill">
                        <span class="cc-stat-num">{{ $cartCount }}</span>
                        <span class="cc-stat-lbl">In cart</span>
                    </div>
                </div>
            </div>

            {{-- ===================== CUSTOMER BENTO ROW ===================== --}}
            <div class="grid grid-cols-1 md:grid-cols-[1.6fr_1fr] gap-5 mb-10">
                <a href="{{ route('recommendations.index') }}" class="cc-feature-card" style="text-decoration:none;">
                    <span class="cc-feature-eyebrow">✨ AI picks</span>
                    <p class="cc-feature-title">Recommended for you</p>
                    <p class="cc-feature-sub">Matched to your taste and order history — see how well each item fits.</p>
                    <span class="btn-mustard">See your top matches</span>
                </a>

                <div class="grid grid-cols-1 gap-5">
                    <a href="{{ route('favorites.index') }}" class="card-plain cc-stat-tile">
                        <div class="cc-icon-badge cc-icon-badge-clay">❤️</div>
                        <div>
                            <p class="cc-stat-tile-num">{{ $favoriteCount }}</p>
                            <p class="cc-stat-tile-lbl">Saved favorites</p>
                        </div>
                    </a>
                    <a href="{{ route('cart.index') }}" class="card-plain cc-stat-tile">
                        <div class="cc-icon-badge cc-icon-badge-mustard">🛒</div>
                        <div>
                            <p class="cc-stat-tile-num">{{ $cartCount }}</p>
                            <p class="cc-stat-tile-lbl">Items in cart</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ===================== CUSTOMER CHARTS ===================== --}}
            <div class="grid grid-cols-1 md:grid-cols-[1.6fr_1fr] gap-5 mb-10">
                <div class="card-plain" style="padding:1.5rem;">
                    <div class="cc-chart-head">
                        <div class="cc-icon-badge cc-icon-badge-mustard">💳</div>
                        <div>
                            <h4>Your spending</h4>
                            <p>Last 6 months, paid orders only</p>
                        </div>
                    </div>
                    <canvas id="dashSpendingTrend" height="120"></canvas>
                </div>
                <div class="card-plain" style="padding:1.5rem;">
                    <div class="cc-chart-head">
                        <div class="cc-icon-badge cc-icon-badge-sage">🥧</div>
                        <div>
                            <h4>Where it goes</h4>
                            <p>Spend by category</p>
                        </div>
                    </div>
                    <canvas id="dashCategorySpend" height="120"></canvas>
                </div>
            </div>
        @endif

        {{-- ===================== CATEGORIES ===================== --}}
        <div class="cc-section-head">
            <h3>{{ $isAdmin ? 'Categories' : 'Browse by category' }}</h3>
            @if ($isAdmin)
                <a href="{{ route('admin.categories.index') }}" class="cc-see-all">Manage categories →</a>
            @else
                <a href="{{ route('menu.index') }}" class="cc-see-all">See full menu →</a>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-10 cc-animate-stagger">
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
            <div class="cc-section-head">
                <h3>Recent orders</h3>
                <a href="{{ route('orders.index') }}" class="cc-see-all">View all orders →</a>
            </div>
            <div class="card-plain" style="overflow:hidden;">
                @forelse ($recentOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="cc-timeline-row">
                        <div class="cc-icon-badge cc-icon-badge-ink" style="font-size:1.1rem;">{{ $orderStatusIcon($order->status) }}</div>
                        <div class="cc-timeline-main">
                            <p class="cc-timeline-title">Order #{{ $order->id }}</p>
                            <p class="cc-timeline-meta">{{ $order->created_at->format('M j, g:i A') }}</p>
                        </div>
                        <span class="badge badge-mustard">{{ $order->status }}</span>
                        <span class="font-mono" style="min-width:5.5rem; text-align:right; font-weight:700;">{{ number_format($order->total_price, 2) }} EGP</span>
                    </a>
                @empty
                    <p style="padding: 1.5rem; color: var(--cc-text-muted); font-size:0.875rem;">No orders yet — head to the menu to place your first one.</p>
                @endforelse
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            const ink = cssVar('--cc-ink') || '#2B1B10';
            const mustard = cssVar('--cc-mustard') || '#E29A2E';
            const sage = cssVar('--cc-sage') || '#4C9A6B';
            const clay = cssVar('--cc-clay') || '#C1543D';
            const line = cssVar('--cc-line') || '#ECE3D6';

            @if ($isAdmin)
                new Chart(document.getElementById('dashOrdersTrend'), {
                    type: 'line',
                    data: {
                        labels: @json($ordersTrend->pluck('label')),
                        datasets: [{
                            label: 'Orders',
                            data: @json($ordersTrend->pluck('total')),
                            borderColor: mustard,
                            backgroundColor: mustard + '26',
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
                            backgroundColor: mustard + '26',
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
                            backgroundColor: [mustard, sage, clay, '#8C7A68', ink, '#B3701A'],
                            borderWidth: 0,
                        }],
                    },
                    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } },
                });
            @endif
        });
    </script>
</x-app-layout>
