<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * List orders for logged-in user
     */
    public function index()
    {
        $user = Auth::user();

        $query = Order::with(['items.product', 'payment'])
            ->latest();

        // ✅ Strong rule: non-admin users can only see their own orders
        if (!$user->hasAnyRole(['admin', 'superadmin'])) {
            $query->where('user_id', $user->id);
        }

        $orders = $query->get();

        foreach ($orders as $order) {
            $order->status = $this->resolveStatus($order);
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * ✅ PAYMENT HISTORY (CLIENTS SEE OWN ONLY)
     * - non-admin users see only their own orders/payments
     * - admin/superadmin can see all (same rule style as index)
     */
    public function paymentHistory()
    {
        $user = Auth::user();

        $query = Order::with(['payment'])
            ->latest();

        // ✅ Strong rule: non-admin users can only see their own payment history
        if (!$user->hasAnyRole(['admin', 'superadmin'])) {
            $query->where('user_id', $user->id);
        }

        $orders = $query->get();

        foreach ($orders as $order) {
            $order->status = $this->resolveStatus($order);
        }

        return view('payments.history', compact('orders'));
    }

    /**
     * Show single order + checkout options
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // ✅ Strong rule: non-admin users can ONLY view own orders
        if (!$user->hasAnyRole(['admin', 'superadmin']) && $order->user_id !== $user->id) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);

        $order->status = $this->resolveStatus($order);

        $methods = PaymentMethod::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.show', compact('order', 'methods'));
    }

    /**
     * ✅ RECEIPT PAGE (print-friendly)
     * - non-admin can print only own approved orders
     * - admin/superadmin can print any approved order
     */
    public function receipt(Order $order, Request $request)
    {
        $user = Auth::user();

        // ✅ Strong rule: non-admin users can ONLY access own receipt
        if (!$user->hasAnyRole(['admin', 'superadmin']) && $order->user_id !== $user->id) {
            abort(403);
        }

        $order->load(['items.product', 'payment', 'user']);

        // Only allow receipt if payment is approved
        abort_if(!$order->payment || $order->payment->status !== 'approved', 403);

        // date to display
        $receiptDate = $order->payment->paid_at ?? $order->created_at;

        return view('orders.receipt', [
            'order' => $order,
            'receiptDate' => $receiptDate,
            'autoprint' => $request->boolean('autoprint'),
        ]);
    }

    /**
     * Resolve order status from payment
     */
    private function resolveStatus(Order $order): string
    {
        if (!$order->payment) {
            return 'pending';
        }

        return match ($order->payment->status) {
            'approved' => 'completed',
            'rejected' => 'rejected',
            default => 'pending',
        };
    }

    /**
     * Update order status (admin & superadmin only)
     */
    public function update(Request $request, Order $order)
    {
        $user = Auth::user();

        // Only admin & superadmin can update order status
        if (!$user->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        DB::transaction(function () use ($request, $order) {
            // Lock order row to prevent double updates under concurrency
            $order = Order::whereKey($order->id)->lockForUpdate()->first();

            $order->load(['payment', 'items']);

            if (!$order->payment) {
                abort(400, 'No payment found for this order.');
            }

            // ✅ If rejected: return stocks ONCE
            if ($request->status === 'rejected' && is_null($order->inventory_reverted_at)) {
                foreach ($order->items as $item) {
                    Product::whereKey($item->product_id)
                        ->increment('stock_quantity', (int) $item->quantity);
                }

                $order->inventory_reverted_at = now();
                $order->save();
            }

            // Update payment status
            $order->payment->update([
                'status' => $request->status,
            ]);
        });

        // ✅ if approved -> go to receipt and auto print
        if ($request->status === 'approved') {
            return redirect()
                ->route('orders.receipt', ['order' => $order->id, 'autoprint' => 1])
                ->with('success', 'Order approved. Printing receipt...');
        }

        return back()->with('success', 'Order status updated successfully.');
    }
}
