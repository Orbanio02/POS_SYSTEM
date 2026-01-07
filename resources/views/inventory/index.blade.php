@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">
    Inventory Management
</h1>

<!-- ================= MOBILE VIEW ================= -->
<div class="md:hidden space-y-4">
    @forelse ($products as $product)
        @php
            $isLowStock = method_exists($product, 'isLowStock')
                ? $product->isLowStock()
                : $product->stock_quantity <= 5;

            $logs = method_exists($product, 'inventoryLogs')
                ? $product->inventoryLogs
                : collect();

            $latestLog = $logs->sortByDesc('created_at')->first();
        @endphp

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 space-y-3">

            <div class="text-lg font-semibold">
                {{ $product->name }}
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                SKU: {{ $product->sku ?? '—' }}
            </div>

            <div class="text-sm font-semibold">
                Stock:
                <span class="{{ $isLowStock ? 'text-red-600' : 'text-green-600' }}">
                    {{ $product->stock_quantity }}
                </span>
                @if ($isLowStock)
                    <div class="text-xs text-red-500 mt-1">Low stock</div>
                @endif
            </div>

            <!-- ADJUST FORM -->
            <form method="POST"
                  action="{{ route('inventory.adjust', $product) }}"
                  class="flex flex-col gap-2">
                @csrf

                <div class="flex gap-2">
                    <select name="type"
                        class="w-20 border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                        <option value="increase">+</option>
                        <option value="decrease">−</option>
                    </select>

                    <input type="number"
                           name="quantity"
                           min="1"
                           required
                           class="flex-1 border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white"
                           placeholder="Qty">
                </div>

                <input type="text"
                       name="reason"
                       class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white"
                       placeholder="Reason">

                <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 w-full">
                    Apply
                </button>
            </form>

            <!-- LATEST LOG ONLY -->
            <div class="pt-2 text-xs text-gray-700 dark:text-gray-300">
                @if (!$latestLog)
                    <span class="text-gray-400">No logs</span>
                @else
                    <div>
                        <span class="font-semibold">
                            {{ $latestLog->change > 0 ? '+' : '' }}{{ $latestLog->change }}
                        </span>
                        → {{ $latestLog->stock_after }}
                        <br>
                        <span class="text-gray-500">
                            {{ $latestLog->reason ?? '—' }}
                            ({{ $latestLog->created_at->diffForHumans() }})
                        </span>
                    </div>
                @endif
            </div>

        </div>
    @empty
        <p class="text-center text-gray-500">No products found.</p>
    @endforelse
</div>

<!-- ================= DESKTOP / TABLET VIEW ================= -->
<div class="hidden md:block overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
    <table class="w-full text-sm text-left border-collapse">
        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="p-3">Product</th>
                <th class="p-3">SKU</th>
                <th class="p-3 text-center">Current Stock</th>
                <th class="p-3">Adjust Stock</th>
                <th class="p-3">Latest Log</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $product)
                @php
                    $isLowStock = method_exists($product, 'isLowStock')
                        ? $product->isLowStock()
                        : $product->stock_quantity <= 5;

                    $logs = method_exists($product, 'inventoryLogs')
                        ? $product->inventoryLogs
                        : collect();

                    $latestLog = $logs->sortByDesc('created_at')->first();
                @endphp

                <tr class="border-t dark:border-gray-700">
                    <td class="p-3 font-medium text-gray-800 dark:text-gray-100">
                        {{ $product->name }}
                    </td>

                    <td class="p-3 text-gray-600 dark:text-gray-400">
                        {{ $product->sku ?? '—' }}
                    </td>

                    <td class="p-3 text-center font-bold">
                        <span class="{{ $isLowStock ? 'text-red-600' : 'text-green-600' }}">
                            {{ $product->stock_quantity }}
                        </span>
                        @if ($isLowStock)
                            <div class="text-xs text-red-500 mt-1">Low stock</div>
                        @endif
                    </td>

                    <td class="p-3">
                        <form method="POST"
                              action="{{ route('inventory.adjust', $product) }}"
                              class="flex flex-wrap items-center gap-2">
                            @csrf

                            <select name="type"
                                class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                                <option value="increase">+</option>
                                <option value="decrease">−</option>
                            </select>

                            <input type="number"
                                   name="quantity"
                                   min="1"
                                   required
                                   class="w-20 border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white"
                                   placeholder="Qty">

                            <input type="text"
                                   name="reason"
                                   class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white"
                                   placeholder="Reason">

                            <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                Apply
                            </button>
                        </form>
                    </td>

                    <!-- LATEST LOG ONLY -->
                    <td class="p-3 text-xs text-gray-700 dark:text-gray-300">
                        @if (!$latestLog)
                            <span class="text-gray-400">No logs</span>
                        @else
                            <div>
                                <span class="font-semibold">
                                    {{ $latestLog->change > 0 ? '+' : '' }}{{ $latestLog->change }}
                                </span>
                                → {{ $latestLog->stock_after }}
                                <br>
                                <span class="text-gray-500">
                                    {{ $latestLog->reason ?? '—' }}
                                    ({{ $latestLog->created_at->diffForHumans() }})
                                </span>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
                        No products found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- SUCCESS SWEETALERT --}}
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: 'Success',
            text: @json(session('success')),
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    });
</script>
@endif

@endsection
