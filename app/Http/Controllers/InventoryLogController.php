<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryLogController extends Controller
{
    /**
     * Display inventory list with recent logs
     */
    public function index()
    {
        $products = Product::with([
            'inventoryLogs' => function ($query) {
                $query->latest()->limit(5);
            }
        ])
            ->orderBy('name')
            ->get();

        return view('inventory.index', compact('products'));
    }

    /**
     * Adjust product stock (increase / decrease)
     */
    public function adjust(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'type'     => 'required|in:increase,decrease',
            'reason'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($product, $data) {

            // Lock product row
            $product = Product::where('id', $product->id)
                ->lockForUpdate()
                ->first();

            $beforeStock = $product->stock_quantity;
            $qty = $data['quantity'];

            if ($data['type'] === 'decrease') {
                if ($product->stock_quantity < $qty) {
                    abort(400, 'Insufficient stock.');
                }

                $product->decrement('stock_quantity', $qty);
                $logType = 'out';
            } else {
                $product->increment('stock_quantity', $qty);
                $logType = 'in';
            }

            $afterStock = $product->stock_quantity;

            // ✅ INVENTORY LOG (MATCHES DATABASE SCHEMA)
            InventoryLog::create([
                'product_id'   => $product->id,
                'type'         => $logType,
                'quantity'     => $qty,
                'before_stock' => $beforeStock,
                'after_stock'  => $afterStock,
                'reference'    => $data['reason'] ?? 'Manual adjustment',
                'user_id'      => auth()->id(),
            ]);
        });

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Show low stock products
     */
    public function lowStock()
    {
        $products = Product::whereColumn(
            'stock_quantity',
            '<=',
            'low_stock_threshold'
        )
            ->orderBy('stock_quantity')
            ->get();

        return view('inventory.low-stock', compact('products'));
    }
}
