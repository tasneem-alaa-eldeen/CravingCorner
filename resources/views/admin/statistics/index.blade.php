<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Statistics</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:2rem;">
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

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div class="card-plain" style="overflow:hidden;">
                <div style="padding:0.75rem 1.1rem; border-bottom:1px solid var(--cc-line); font-weight:700; font-family:'Baloo 2',sans-serif;">Top 5 selling items</div>
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
            </div>
        </div>

        <p style="margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted);">
            For anything not shown here, ask the <a href="{{ route('chatbot.index') }}" style="color: var(--cc-mustard-dark); font-weight:600;">assistant</a> — e.g. "which category is most popular?"
        </p>
    </div>
</x-app-layout>
