<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\TransactionStatusNote; // ✅ ONLY NEW IMPORT
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
     */
    public function paymentHistory()
    {
        $user = Auth::user();

        $query = Order::with(['payment'])
            ->latest();

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
     * ✅ RECEIPT PAGE
     */
    public function receipt(Order $order, Request $request)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'superadmin']) && $order->user_id !== $user->id) {
            abort(403);
        }

        $order->load(['items.product', 'payment', 'user']);

        abort_if(!$order->payment || $order->payment->status !== 'approved', 403);

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

        if (!$user->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'note' => 'required|string|min:3',
        ]);

        DB::transaction(function () use ($request, $order) {

            $order = Order::whereKey($order->id)->lockForUpdate()->first();
            $order->load(['payment', 'items']);

            if (!$order->payment) {
                abort(400, 'No payment found for this order.');
            }

            if ($request->status === 'rejected' && is_null($order->inventory_reverted_at)) {
                foreach ($order->items as $item) {
                    Product::whereKey($item->product_id)
                        ->increment('stock_quantity', (int) $item->quantity);
                }

                $order->inventory_reverted_at = now();
                $order->save();
            }

            $order->payment->update([
                'status' => $request->status,
            ]);

            // ✅ STATUS NOTE (NEW FEATURE ONLY)
            TransactionStatusNote::create([
                'transaction_id' => $order->payment->id,
                'status' => $request->status,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);
        });

        if ($request->status === 'approved') {
            return redirect()
                ->route('orders.receipt', ['order' => $order->id, 'autoprint' => 1])
                ->with('success', 'Order approved. Printing receipt...');
        }

        return back()->with('success', 'Order status updated successfully.');
    }
}
