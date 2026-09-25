<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalFoodItems = FoodItem::count();
        $totalBeverages = Beverage::count();
        $totalOrders = Order::count();
        $ordersToday = Order::whereDate('created_at', today())->count();
        $totalSales = Order::where('payment_status', 'paid')->sum('total_price');
        $salesToday = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_price');

        $topItems = OrderItem::select('itemable_type', 'itemable_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('itemable_type', 'itemable_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(function ($row) {
                $item = $row->itemable_type::find($row->itemable_id);
                return ['name' => $item->name ?? 'Deleted item', 'quantity' => $row->total_qty];
            });

        $lowStock = FoodItem::where('available_quantity', '<=', 5)->get(['name', 'available_quantity'])
            ->concat(Beverage::where('available_quantity', '<=', 5)->get(['name', 'available_quantity']));

        // ---- Chart data -------------------------------------------------

        // Sales for the last 7 days (paid orders only), oldest -> newest.
        $salesByDay = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as day, SUM(total_price) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $salesTrend = collect(range(6, 0))->map(function ($daysAgo) use ($salesByDay) {
            $date = Carbon::today()->subDays($daysAgo);
            $key = $date->toDateString();
            return [
                'label' => $date->format('D'),
                'total' => (float) ($salesByDay[$key] ?? 0),
            ];
        });

        // Order status breakdown (for a doughnut chart).
        $orderStatusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Revenue by category (top 6), for a bar/pie chart.
        $categoryBreakdown = OrderItem::select('itemable_type', 'itemable_id', DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('itemable_type', 'itemable_id')
            ->get()
            ->map(function ($row) {
                $item = $row->itemable_type::with('category')->find($row->itemable_id);
                return [
                    'category' => $item->category->name ?? 'Uncategorized',
                    'revenue' => (float) $row->revenue,
                ];
            })
            ->groupBy('category')
            ->map(fn ($rows) => $rows->sum('revenue'))
            ->sortDesc()
            ->take(6);

        return view('admin.statistics.index', compact(
            'totalCustomers', 'totalFoodItems', 'totalBeverages', 'totalOrders',
            'ordersToday', 'totalSales', 'salesToday', 'topItems', 'lowStock',
            'salesTrend', 'orderStatusCounts', 'categoryBreakdown'
        ));
    }
}
