@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold ">Payment History</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Your payment and order history.
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($orders as $order)
                        @php
                            $paymentStatus = optional($order->payment)->status ?? 'pending';
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-4 py-3 font-medium">
                                {{ $order->order_number ?? '#' . $order->id }}
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                @if ($paymentStatus === 'approved') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200
                                @elseif ($paymentStatus === 'rejected')
                                    bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200
                                @else
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200 @endif
                            ">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                            <td class="p-3 text-center">
                                <a href="{{ route('orders.show', $order) }}"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                                    title="View Order">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                         c4.477 0 8.268 2.943 9.542 7
                                                         -1.274 4.057-5.065 7-9.542 7
                                                         -4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                No payment history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
