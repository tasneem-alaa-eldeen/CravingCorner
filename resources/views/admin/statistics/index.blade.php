<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Statistics</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:2rem;" class="cc-animate-stagger">
            <div class="card-ticket" style="padding:1rem 1.25rem;">
                <p style="font-size:0.7rem; text-transform:uppercase; color: var(--cc-text-muted);">Customers</p>
                <p class="font-mono" style="font-size:1.6rem; margin-top:0.25rem;">{{ $totalCustomers }}</p>
            </div>
            <div class="card-ticket" style="padding:1rem 1.25rem;">
                <p style="font-size:0.7rem; text-transform:uppercase; color: var(--cc-text-muted);">Orders (total / today)</p>
                <p class="font-mono" style="font-size:1.6rem; margin-top:0.25rem;">{{ $totalOrders }} / {{ $ordersToday }}</p>
            </div>
            <div class="card-ticket" style="padding:1rem 1.25rem;">
                <p style="font-size:0.7rem; text-transform:uppercase; color: var(--cc-text-muted);">Sales (total)</p>
                <p class="font-mono" style="font-size:1.6rem; margin-top:0.25rem;">{{ number_format($totalSales, 2) }} EGP</p>
            </div>
            <div class="card-ticket" style="padding:1rem 1.25rem;">
                <p style="font-size:0.7rem; text-transform:uppercase; color: var(--cc-text-muted);">Sales (today)</p>
                <p class="font-mono" style="font-size:1.6rem; margin-top:0.25rem; color: var(--cc-mustard-dark);">{{ number_format($salesToday, 2) }} EGP</p>
            </div>
            <div class="card-ticket" style="padding:1rem 1.25rem;">
                <p style="font-size:0.7rem; text-transform:uppercase; color: var(--cc-text-muted);">Food items</p>
                <p class="font-mono" style="font-size:1.6rem; margin-top:0.25rem;">{{ $totalFoodItems }}</p>
            </div>
            <div class="card-ticket" style="padding:1rem 1.25rem;">
                <p style="font-size:0.7rem; text-transform:uppercase; color: var(--cc-text-muted);">Beverages</p>
                <p class="font-mono" style="font-size:1.6rem; margin-top:0.25rem;">{{ $totalBeverages }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:1.5rem; margin-bottom:2rem;">
            <div class="card-plain" style="padding:1.1rem;">
                <p style="font-weight:700; font-family:'Baloo 2',sans-serif; margin-bottom:0.6rem;">Sales — last 7 days</p>
                <canvas id="salesTrendChart" height="140"></canvas>
            </div>
            <div class="card-plain" style="padding:1.1rem;">
                <p style="font-weight:700; font-family:'Baloo 2',sans-serif; margin-bottom:0.6rem;">Orders by status</p>
                <canvas id="orderStatusChart" height="140"></canvas>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div class="card-plain" style="overflow:hidden;">
                <div style="padding:0.75rem 1.1rem; border-bottom:1px solid var(--cc-line); font-weight:700; font-family:'Baloo 2',sans-serif;">Top 5 selling items</div>
                <div style="padding:1rem 1.1rem 0.25rem;">
                    <canvas id="topItemsChart" height="160"></canvas>
                </div>
                @forelse ($topItems as $row)
                    <div style="display:flex; justify-content:space-between; padding:0.5rem 1.1rem; border-bottom:1px solid var(--cc-line); font-size:0.875rem;">
                        <span>{{ $row['name'] }}</span>
                        <span class="font-mono">{{ $row['quantity'] }} sold</span>
                    </div>
                @empty
                    <p style="padding:1.25rem; color: var(--cc-text-muted); font-size:0.875rem;">No sales yet.</p>
                @endforelse
            </div>

            <div class="card-plain" style="overflow:hidden;">
                <div style="padding:0.75rem 1.1rem; border-bottom:1px solid var(--cc-line); font-weight:700; font-family:'Baloo 2',sans-serif;">Low stock (≤ 5)</div>
                @forelse ($lowStock as $item)
                    <div style="display:flex; justify-content:space-between; padding:0.5rem 1.1rem; border-bottom:1px solid var(--cc-line); font-size:0.875rem;">
                        <span>{{ $item->name }}</span>
                        <span class="badge badge-clay">{{ $item->available_quantity }} left</span>
                    </div>
                @empty
                    <p style="padding:1.25rem; color: var(--cc-text-muted); font-size:0.875rem;">Everything is well stocked.</p>
                @endforelse

                <div style="padding:0.75rem 1.1rem; border-top:1px solid var(--cc-line); border-bottom:1px solid var(--cc-line); font-weight:700; font-family:'Baloo 2',sans-serif;">Revenue by category</div>
                <div style="padding:1rem 1.1rem;">
                    <canvas id="categoryChart" height="160"></canvas>
                </div>
            </div>
        </div>

        <p style="margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted);">
            For anything not shown here, ask the <a href="{{ route('chatbot.index') }}" style="color: var(--cc-mustard-dark); font-weight:600;">assistant</a> — e.g. "which category is most popular?"
        </p>
    </div>

    @push('scripts')
    @endpush

    <script>
        // Chart.js is loaded globally in layouts/app.blade.php.
        document.addEventListener('DOMContentLoaded', function () {
            const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            const ink = cssVar('--cc-ink') || '#1F3B2C';
            const mustard = cssVar('--cc-mustard') || '#E2A63B';
            const sage = cssVar('--cc-sage') || '#6B9080';
            const clay = cssVar('--cc-clay') || '#C1443B';
            const line = cssVar('--cc-line') || '#D8CFBA';

            new Chart(document.getElementById('salesTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($salesTrend->pluck('label')),
                    datasets: [{
                        label: 'Sales (EGP)',
                        data: @json($salesTrend->pluck('total')),
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

            const statusLabels = @json($orderStatusCounts->keys());
            const statusData = @json($orderStatusCounts->values());
            new Chart(document.getElementById('orderStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: [mustard, sage, clay, ink, '#C98A22', '#7A7566'],
                        borderWidth: 0,
                    }],
                },
                options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } },
            });

            const topLabels = @json($topItems->pluck('name'));
            const topData = @json($topItems->pluck('quantity'));
            new Chart(document.getElementById('topItemsChart'), {
                type: 'bar',
                data: {
                    labels: topLabels,
                    datasets: [{ label: 'Units sold', data: topData, backgroundColor: mustard, borderRadius: 6 }],
                },
                options: {
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, grid: { color: line } }, y: { grid: { display: false } } },
                },
            });

            new Chart(document.getElementById('categoryChart'), {
                type: 'pie',
                data: {
                    labels: @json($categoryBreakdown->keys()),
                    datasets: [{
                        data: @json($categoryBreakdown->values()),
                        backgroundColor: [mustard, sage, clay, '#C98A22', ink, '#7A7566'],
                        borderWidth: 0,
                    }],
                },
                options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } },
            });
        });
    </script>
</x-app-layout>
