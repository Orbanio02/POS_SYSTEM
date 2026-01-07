@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6 text-red-600">
    ⚠ Low Stock Alerts
</h1>

@if ($products->isEmpty())
    <div class="p-4 bg-green-100 text-green-800 rounded">
        All products have sufficient stock.
    </div>
@else
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-200 dark:bg-gray-700">
                <tr>
                    <th class="p-3">Product</th>
                    <th class="p-3">SKU</th>
                    <th class="p-3 text-center">Stock</th>
                    <th class="p-3 text-center">Threshold</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="border-t dark:border-gray-700">
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">{{ $product->sku ?? '—' }}</td>
                    <td class="p-3 text-center text-red-600 font-bold">
                        {{ $product->stock_quantity }}
                    </td>
                    <td class="p-3 text-center">
                        {{ $product->low_stock_threshold }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection