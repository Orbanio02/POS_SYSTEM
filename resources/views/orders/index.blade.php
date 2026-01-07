@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <h1 class="text-2xl font-bold">Orders</h1>

    {{-- MOBILE --}}
    <div class="md:hidden space-y-3">
        @foreach ($orders as $order)
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow space-y-2">
                <div class="font-bold">Order #{{ $order->order_number }}</div>

                <div class="flex justify-between text-sm">
                    <span>Total</span>
                    <span>₱{{ number_format($order->total, 2) }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Status</span>
                    <span>{{ ucfirst($order->status) }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Date</span>
                    <span>{{ $order->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- DESKTOP --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 shadow rounded-lg">
        <table class="w-full text-sm">
            <thead class="bg-gray-200 dark:bg-gray-700">
                <tr>
                    <th class="p-3">Order #</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td class="p-3 text-center">{{ $order->order_number }}</td>
                        <td class="p-3 text-center">{{ ucfirst($order->status) }}</td>
                        <td class="p-3 text-center">₱{{ number_format($order->total,2) }}</td>
                        <td class="p-3 text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
