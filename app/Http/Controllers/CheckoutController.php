<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

        $methods = PaymentMethod::where('is_active', true)->get();

        return view('checkout.index', compact('cart', 'total', 'methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|exists:payment_methods,name',
        ]);

        $cart = session('cart', []);

        DB::transaction(function () use ($cart, $request) {

            $order = Order::create([
                'user_id' => Auth::id(),
                'total'   => collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']),
            ]);

            foreach ($cart as $item) {

                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $product->decrement('stock_quantity', $item['quantity']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id'=> $product->id,
                    'quantity'  => $item['quantity'],
                    'unit_price'=> $item['price'],
                    'total_price'=> $item['price'] * $item['quantity'],
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'user_id'  => Auth::id(),
                'method'   => $request->payment_method,
                'amount'   => $order->total,
                'status'   => 'pending',
                'paid_at'  => now(),
            ]);
        });

        session()->forget('cart');

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully.');
    }
}
