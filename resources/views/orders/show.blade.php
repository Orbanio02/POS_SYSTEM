@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 text-gray-900 dark:text-gray-100">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">
                Order #{{ $order->order_number }}
            </h1>

            <span
                class="px-3 py-1 rounded text-sm font-semibold
            @if ($order->status === 'completed') bg-green-100 text-green-800
            @elseif ($order->status === 'pending') bg-yellow-100 text-yellow-800
            @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        @if ($order->payment && $order->payment->status === 'approved')
            <a href="{{ route('orders.receipt', $order) }}" target="_blank"
                class="inline-flex items-center px-4 py-2 rounded bg-green-600 text-white hover:bg-green-800">
                Print Receipt
            </a>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-right">Price</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="p-3">{{ $item->product->name }}</td>
                            <td class="p-3 text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                            <td class="p-3 text-center">{{ $item->quantity }}</td>
                            <td class="p-3 text-right font-semibold">
                                ₱{{ number_format($item->total_price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-between px-4 py-3 bg-gray-50 dark:bg-gray-700">
                <span class="font-semibold">Total</span>
                <span class="text-xl font-bold text-green-600">
                    ₱{{ number_format($order->total, 2) }}
                </span>
            </div>
        </div>

        @role(['admin', 'superadmin'])
            @if ($order->payment)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Update Order Status</h2>

                <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf
                @method('PUT')

                <select name="status"
                    class="w-full p-2 mb-4 rounded border bg-white text-gray-900 border-gray-300
                       dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600
                       focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 appearance-none">
                    <option value="pending" @selected($order->payment->status === 'pending')>Pending</option>
                    <option value="approved" @selected($order->payment->status === 'approved')>Approved</option>
                    <option value="rejected" @selected($order->payment->status === 'rejected')>Rejected</option>
                </select>

                <button class="w-full py-2 rounded bg-blue-600 text-white hover:bg-blue-700
                           dark:bg-blue-500 dark:hover:bg-blue-600">
                    Update Status
                </button>
                </form>
            </div>
            @endif
        @endrole

    </div>
@endsection
