<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        // A customer may only ever see their own order.
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items.itemable');

        return view('orders.show', compact('order'));
    }

    public function store(Request $request, CartController $cartController)
    {
        $cart = $cartController->hydrateCart();

        if (empty($cart)) {
            return back()->with('status', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cart, $request) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => collect($cart)->sum('subtotal'),
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'itemable_id' => $line['item']->id,
                    'itemable_type' => get_class($line['item']),
                    'quantity' => $line['quantity'],
                    'price' => $line['item']->price,
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('status', 'Order placed!');
    }

    public function cancel(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        // Only allow cancelling while the kitchen hasn't started on it.
        abort_unless($order->status === 'pending', 422, 'This order can no longer be cancelled.');

        $order->update(['status' => 'cancelled']);

        return back()->with('status', 'Order cancelled.');
    }
}
