<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
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

        return view('admin.statistics.index', compact(
            'totalCustomers', 'totalFoodItems', 'totalBeverages', 'totalOrders',
            'ordersToday', 'totalSales', 'salesToday', 'topItems', 'lowStock'
        ));
    }
}
