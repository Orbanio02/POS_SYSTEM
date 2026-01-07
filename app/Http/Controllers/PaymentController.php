<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Payment history (Management → Payments)
     */
    public function index()
    {
        $orders = Order::with(['payment', 'user'])
            ->latest()
            ->get();

        return view('payments.index', compact('orders'));
    }

    /**
     * Store payment (CHECKOUT)
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'method' => 'required|string|max:50',
        ]);

        // ✅ Create payment correctly
        Payment::create([
            'order_id' => $order->id,
            'user_id'  => Auth::id(),      // 🔑 FIXED
            'method'   => $request->method,
            'amount'   => $order->total,
            'status'   => 'pending',
            'paid_at'  => now(),            // 🔑 FIXED
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Payment submitted successfully.');
    }
}
