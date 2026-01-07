@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Payment History
        </h1>

        <!-- MOBILE VIEW -->
        <div class="md:hidden space-y-4">
            @foreach ($orders as $order)
                @php $paymentStatus = optional($order->payment)->status ?? 'pending'; @endphp

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 space-y-2">

                    <div class="font-bold">
                        Order #: {{ $order->order_number }}
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">Customer:</span>
                        <span class="font-medium">{{ optional($order->user)->name ?? '—' }}</span>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">Total:</span>
                        <span class="font-semibold">₱{{ number_format($order->total, 2) }}</span>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                        @if ($paymentStatus === 'approved') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                        @elseif ($paymentStatus === 'rejected')
                            bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                        @else
                            bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                            {{ ucfirst($paymentStatus) }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('orders.show', $order) }}"
                            class="inline-flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                            View Order
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- DESKTOP/TABLET VIEW -->
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="p-3 text-left text-gray-700 dark:text-gray-200">Orders #</th>
                        <th class="p-3 text-left text-gray-700 dark:text-gray-200">Customer</th>
                        <th class="p-3 text-right text-gray-700 dark:text-gray-200">Total</th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200">Status</th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($orders as $order)
                        @php $paymentStatus = optional($order->payment)->status ?? 'pending'; @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="p-3 text-gray-900 dark:text-gray-100">
                                {{ $order->order_number }}
                            </td>
                            <td class="p-3 text-gray-900 dark:text-gray-100 font-medium">
                                {{ optional($order->user)->name ?? '—' }}
                            </td>
                            <td class="p-3 text-right font-semibold text-gray-900 dark:text-gray-100">
                                ₱{{ number_format($order->total, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                @if ($paymentStatus === 'approved') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif ($paymentStatus === 'rejected')
                                    bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
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
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
