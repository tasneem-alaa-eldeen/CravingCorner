<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Beverage;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->hydrateCart();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:food,beverage'],
            'id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $key = $validated['type'].'_'.$validated['id'];
        $cart = session('cart', []);
        $cart[$key] = [
            'type' => $validated['type'],
            'id' => $validated['id'],
            'quantity' => ($cart[$key]['quantity'] ?? 0) + $validated['quantity'],
        ];
        session(['cart' => $cart]);

        return back()->with('status', 'Added to cart.');
    }

    public function remove(string $key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return back()->with('status', 'Removed from cart.');
    }

    /**
     * Turns the raw session cart (type/id/quantity) into real model
     * instances + line totals, and drops any item that no longer exists.
     */
    public function hydrateCart(): array
    {
        $raw = session('cart', []);
        $hydrated = [];

        foreach ($raw as $key => $line) {
            $model = $line['type'] === 'food'
                ? FoodItem::find($line['id'])
                : Beverage::find($line['id']);

            if (! $model) {
                continue;
            }

            $hydrated[$key] = [
                'key' => $key,
                'item' => $model,
                'type' => $line['type'],
                'quantity' => $line['quantity'],
                'subtotal' => $model->price * $line['quantity'],
            ];
        }

        return $hydrated;
    }
}
