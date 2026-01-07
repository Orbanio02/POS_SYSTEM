<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Product $product, Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            // Increase quantity if already exists
            $cart[$product->id]['quantity'] += 1;
        } else {
            // Add new item
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart.');
    }

    /**
     * Update all quantities at once
     */
    public function updateAll(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->quantities ?? [] as $productId => $quantity) {
            if (isset($cart[$productId]) && $quantity > 0) {
                $cart[$productId]['quantity'] = (int) $quantity;
            }
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove item from cart
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        unset($cart[$productId]);

        session()->put('cart', $cart);

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared.');
    }
}
