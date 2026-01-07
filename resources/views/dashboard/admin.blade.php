@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight">Sales Dashboard</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Monitoring overview for sales, orders, and inventory.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Updated: <span class="font-semibold">{{ now()->format('M d, Y') }}</span>
                </div>

                @can('payments.index')
                    <a href="{{ route('payments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                          bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700
                          hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-semibold">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="M3 10h18"></path>
                            <path d="M7 15h2"></path>
                            <path d="M11 15h6"></path>
                        </svg>
                        Payments
                    </a>
                @endcan

                @can('inventory.view')
                    <a href="{{ route('inventory.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                          bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700
                          hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-semibold">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 7h-9"></path>
                            <path d="M14 17H5"></path>
                            <circle cx="17" cy="17" r="3"></circle>
                            <circle cx="7" cy="7" r="3"></circle>
                        </svg>
                        Inventory
                    </a>
                @endcan
            </div>
        </div>

        {{-- KPI ROW --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Today --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Today Sales</div>
                        <div class="mt-2 text-2xl font-extrabold">₱{{ number_format($todaySales, 2) }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Approved payments only</div>
                    </div>
                    <div
                        class="p-2 rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20V10"></path>
                            <path d="M18 20V4"></path>
                            <path d="M6 20v-6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Month --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">This Month</div>
                        <div class="mt-2 text-2xl font-extrabold">₱{{ number_format($monthSales, 2) }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ now()->format('F Y') }}</div>
                    </div>
                    <div class="p-2 rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18"></path>
                            <path d="M3 6h18"></path>
                            <path d="M3 18h18"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending Payments</div>
                        <div class="mt-2 text-2xl font-extrabold">{{ $pendingCount }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Needs review</div>
                    </div>
                    <div class="p-2 rounded-xl bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8v4"></path>
                            <path d="M12 16h.01"></path>
                            <path
                                d="M10.29 3.86l-7.4 12.82A2 2 0 0 0 4.62 20h14.76a2 2 0 0 0 1.73-3.32l-7.4-12.82a2 2 0 0 0-3.42 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Low Stock --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Low Stock</div>
                        <div class="mt-2 text-2xl font-extrabold">{{ $lowStockCount }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Below threshold</div>
                    </div>
                    <div class="p-2 rounded-xl bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <path d="M12 22V12"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- CHARTS --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- BIG DAILY --}}
            <div
                class="xl:col-span-2 rounded-2xl
               bg-white dark:bg-gray-800
               shadow border border-gray-200 dark:border-gray-700 p-5">

                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">
                            Daily Sales
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Last 14 days (approved)
                        </p>
                    </div>
                </div>

                <div class="mt-4">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            {{-- SIDE --}}
            <div class="space-y-6">

                {{-- MONTHLY --}}
                <div
                    class="rounded-2xl
                   bg-white dark:bg-gray-800
                   shadow border border-gray-200 dark:border-gray-700 p-5">

                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Monthly
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Last 12 months (approved)
                    </p>

                    <div class="mt-4">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                {{-- YEARLY --}}
                <div
                    class="rounded-2xl
                   bg-white dark:bg-gray-800
                   shadow border border-gray-200 dark:border-gray-700 p-5">

                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Yearly
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        All time (approved)
                    </p>

                    <div class="mt-4">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>

            </div>
        </div>


        {{-- TABLES --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- RECENT ORDERS --}}
            <div
                class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold">Recent Orders</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Latest 8 (payment status)</p>
                    </div>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        View all
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="p-3 text-left">Order</th>
                                <th class="p-3 text-right">Total</th>
                                <th class="p-3 text-center">Payment</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentOrders as $order)
                                @php $paymentStatus = optional($order->payment)->status ?? 'pending'; @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="p-3 font-medium">{{ $order->order_number }}</td>
                                    <td class="p-3 text-right font-semibold">₱{{ number_format($order->total, 2) }}</td>
                                    <td class="p-3 text-center">
                                        <span
                                            class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                        @if ($paymentStatus === 'approved') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200
                                        @elseif ($paymentStatus === 'rejected')
                                            bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200
                                        @else
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200 @endif">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg
              bg-blue-50 text-blue-700 hover:bg-blue-100
              dark:bg-blue-900/30 dark:text-blue-200 dark:hover:bg-blue-900/50
              border border-blue-100 dark:border-blue-900/40 transition"
                                            title="View Order">
                                            {{-- Eye Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-5 text-center text-gray-500 dark:text-gray-400">
                                        No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- LOW STOCK --}}
            <div
                class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5">
                    <h3 class="font-bold">Low Stock Alerts</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Below threshold</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="p-3 text-left">Product</th>
                                <th class="p-3 text-left">SKU</th>
                                <th class="p-3 text-right">Stock</th>
                                <th class="p-3 text-right">Threshold</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($lowStockProducts as $p)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="p-3 font-medium">{{ $p->name }}</td>
                                    <td class="p-3 text-gray-500 dark:text-gray-400">{{ $p->sku }}</td>
                                    <td class="p-3 text-right font-semibold text-red-600 dark:text-red-400">
                                        {{ $p->stock_quantity }}
                                    </td>
                                    <td class="p-3 text-right">{{ $p->low_stock_threshold }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-5 text-center text-gray-500 dark:text-gray-400">
                                        No low-stock items 🎉
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDark = document.documentElement.classList.contains('dark');

        const money = (n) => {
            const x = Number(n || 0);
            return '₱' + x.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };

        /* ✅ PROPER CONTRAST FOR BOTH MODES */
        const tickColor = isDark ? '#f9fafb' : '#111827';
        const gridColor = isDark ?
            'rgba(255,255,255,0.08)' :
            'rgba(0,0,0,0.06)';

        const tooltipBgColor = isDark ?
            'rgba(2,6,23,0.95)' :
            'rgba(255,255,255,0.95)';

        const tooltipTextColor = isDark ? '#f9fafb' : '#111827';

        /* ================= DAILY ================= */
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: @json($dailySales->pluck('date')),
                datasets: [{
                    data: @json($dailySales->pluck('total')),
                    borderColor: 'rgba(16,185,129,1)',
                    backgroundColor: isDark ?
                        'rgba(16,185,129,0.35)' :
                        'rgba(16,185,129,0.25)',
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 2,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: tooltipBgColor,
                        titleColor: tooltipTextColor,
                        bodyColor: tooltipTextColor,
                        callbacks: {
                            label: ctx => money(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: tickColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: tickColor,
                            callback: v => money(v)
                        }
                    }
                }
            }
        });

        /* ================= MONTHLY ================= */
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: @json($monthlySales->pluck('month')),
                datasets: [{
                    data: @json($monthlySales->pluck('total')),
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 2,
                    tension: 0.35
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: tooltipBgColor,
                        titleColor: tooltipTextColor,
                        bodyColor: tooltipTextColor
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: tickColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: tickColor,
                            callback: v => money(v)
                        }
                    }
                }
            }
        });

        /* ================= YEARLY ================= */
        new Chart(document.getElementById('yearlyChart'), {
            type: 'bar',
            data: {
                labels: @json($yearlySales->pluck('year')),
                datasets: [{
                    data: @json($yearlySales->pluck('total')),
                    backgroundColor: isDark ?
                        'rgba(168,85,247,0.55)' :
                        'rgba(168,85,247,0.45)',
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: tooltipBgColor,
                        titleColor: tooltipTextColor,
                        bodyColor: tooltipTextColor
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: tickColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: tickColor,
                            callback: v => money(v)
                        }
                    }
                }
            }
        });
    </script>
@endsection
