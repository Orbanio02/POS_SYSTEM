<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Prevent crashes during fresh install / migrations
        if (!Schema::hasTable('products')) {
            return;
        }

        View::composer('*', function ($view) {

            $lowStockProducts = Product::query()
                ->whereNotNull('low_stock_threshold')
                ->whereColumn(
                    'stock_quantity',
                    '<=',
                    'low_stock_threshold'
                )
                ->orderBy('stock_quantity', 'asc')
                ->get([
                    'id',
                    'name',
                    'sku',
                    'stock_quantity',
                    'low_stock_threshold',
                ]);

            $view->with([
                'lowStockProducts' => $lowStockProducts,
                'lowStockCount'    => $lowStockProducts->count(),
            ]);
        });
    }
}
