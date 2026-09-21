<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Statistics</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase">Customers</p>
                <p class="text-2xl font-semibold mt-1">{{ $totalCustomers }}</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase">Orders (total / today)</p>
                <p class="text-2xl font-semibold mt-1">{{ $totalOrders }} / {{ $ordersToday }}</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase">Sales (total)</p>
                <p class="text-2xl font-semibold mt-1">{{ number_format($totalSales, 2) }} EGP</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase">Sales (today)</p>
                <p class="text-2xl font-semibold mt-1">{{ number_format($salesToday, 2) }} EGP</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase">Food items</p>
                <p class="text-2xl font-semibold mt-1">{{ $totalFoodItems }}</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase">Beverages</p>
                <p class="text-2xl font-semibold mt-1">{{ $totalBeverages }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b font-semibold text-gray-700">Top 5 selling items</div>
                @forelse ($topItems as $row)
                    <div class="flex justify-between px-4 py-2 text-sm border-b last:border-0">
                        <span>{{ $row['name'] }}</span>
                        <span>{{ $row['quantity'] }} sold</span>
                    </div>
                @empty
                    <p class="px-4 py-6 text-sm text-gray-400">No sales yet.</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b font-semibold text-gray-700">Low stock (≤ 5)</div>
                @forelse ($lowStock as $item)
                    <div class="flex justify-between px-4 py-2 text-sm border-b last:border-0">
                        <span>{{ $item->name }}</span>
                        <span style="color:#dc2626;">{{ $item->available_quantity }} left</span>
                    </div>
                @empty
                    <p class="px-4 py-6 text-sm text-gray-400">Everything is well stocked.</p>
                @endforelse
            </div>
        </div>

        <p class="mt-6 text-sm text-gray-500">
            For anything not shown here, just ask the <a href="{{ route('chatbot.index') }}" style="color:#4f46e5;">assistant</a> — e.g. "which category is most popular?"
        </p>
    </div>
</x-app-layout>
