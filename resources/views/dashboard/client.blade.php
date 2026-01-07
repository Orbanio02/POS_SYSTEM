@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">My Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Your order updates & payment status at a glance.
            </p>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Spent (Approved)</div>
                <div class="mt-2 text-2xl font-extrabold">₱{{ number_format($approvedTotal, 2) }}</div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Approved payments only</div>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                <div class="mt-2 text-2xl font-extrabold">{{ $pendingCount }}</div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Waiting for approval</div>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="text-sm text-gray-500 dark:text-gray-400">Approved</div>
                <div class="mt-2 text-2xl font-extrabold">{{ $approvedCount }}</div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Completed payments</div>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="text-sm text-gray-500 dark:text-gray-400">Rejected</div>
                <div class="mt-2 text-2xl font-extrabold">{{ $rejectedCount }}</div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Try again if needed</div>
            </div>

        </div>

        {{-- RECENT ORDERS --}}
        <div
            class="rounded-2xl bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="font-bold">My Recent Orders</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Latest 10</p>
                </div>

                <a href="{{ route('orders.index') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-800 text-sm font-semibold">
                    View All
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
                        @forelse($orders as $order)
                            @php
                                $paymentStatus = optional($order->payment)->status ?? 'pending';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="p-3 font-medium">{{ $order->order_number }}</td>
                                <td class="p-3 text-right font-semibold">₱{{ number_format($order->total, 2) }}</td>
                                <td class="p-3 text-center">
                                    <span
                                        class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                    @if ($paymentStatus === 'approved') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200
                                    @elseif($paymentStatus === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200 @endif">
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
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
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

    </div>
@endsection
