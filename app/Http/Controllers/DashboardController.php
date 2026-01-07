<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ ADMIN / SUPERADMIN DASHBOARD (monitoring)
        // Only these roles can see global monitoring.
        if ($user->hasAnyRole(['admin', 'superadmin'])) {
            return $this->adminDashboard();
        }

        // ✅ Everyone else (client or any non-admin role) sees PERSONAL dashboard only.
        return $this->clientDashboard($user->id);
    }

    /**
     * Admin + Superadmin dashboard (monitoring)
     */
    private function adminDashboard()
    {
        $dailySales = Order::query()
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlySales = Order::query()
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as total")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $yearlySales = Order::query()
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->selectRaw("YEAR(created_at) as year, SUM(total) as total")
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $todaySales = Order::query()
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');

        $monthSales = Order::query()
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $pendingCount = Order::query()
            ->whereHas('payment', fn($q) => $q->where('status', 'pending'))
            ->count();

        $recentOrders = Order::query()
            ->with('payment')
            ->latest()
            ->take(8)
            ->get();

        $lowStockProducts = Product::query()
            ->select('id', 'name', 'sku', 'stock_quantity', 'low_stock_threshold')
            ->whereNotNull('low_stock_threshold')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->take(8)
            ->get();

        $lowStockCount = Product::query()
            ->whereNotNull('low_stock_threshold')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->count();

        return view('dashboard.admin', compact(
            'dailySales',
            'monthlySales',
            'yearlySales',
            'todaySales',
            'monthSales',
            'pendingCount',
            'recentOrders',
            'lowStockProducts',
            'lowStockCount'
        ));
    }

    /**
     * Client dashboard (personal only)
     */
    private function clientDashboard(int $userId)
    {
        $orders = Order::query()
            ->with('payment')
            ->where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $approvedTotal = Order::query()
            ->where('user_id', $userId)
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->sum('total');

        $pendingCount = Order::query()
            ->where('user_id', $userId)
            ->whereHas('payment', fn($q) => $q->where('status', 'pending'))
            ->count();

        $approvedCount = Order::query()
            ->where('user_id', $userId)
            ->whereHas('payment', fn($q) => $q->where('status', 'approved'))
            ->count();

        $rejectedCount = Order::query()
            ->where('user_id', $userId)
            ->whereHas('payment', fn($q) => $q->where('status', 'rejected'))
            ->count();

        return view('dashboard.client', compact(
            'orders',
            'approvedTotal',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }
}
