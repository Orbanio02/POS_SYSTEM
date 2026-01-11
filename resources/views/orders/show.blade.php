@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 text-gray-900 dark:text-gray-100">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">
                Order #{{ $order->order_number }}
            </h1>

            <span
                class="px-3 py-1 rounded-full text-sm font-semibold
            @if ($order->status === 'completed') bg-green-100 text-green-800
            @elseif ($order->status === 'pending') bg-yellow-100 text-yellow-800
            @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <a href="{{ route('payments.history') }}"
            class="inline-flex items-center px-4 py-2 rounded bg-gray-600 text-white hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 mb-4">
            Back
        </a>

        @if ($order->payment && $order->payment->status === 'approved')
            <a href="{{ route('orders.receipt', $order) }}" target="_blank"
                class="inline-flex items-center px-4 py-2 rounded bg-green-600 text-white hover:bg-green-800">
                Print Receipt
            </a>
        @endif

        {{-- ORDER ITEMS --}}
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

        {{-- CLIENT READ-ONLY ADMIN NOTE --}}
        @unlessrole(['admin', 'superadmin'])
            @php
                $latestNote = \App\Models\TransactionStatusNote::with('user')
                    ->where('transaction_id', optional($order->payment)->id)
                    ->latest()
                    ->first();
            @endphp

            @if ($latestNote)
                <div class="bg-blue-50 dark:bg-gray-800 border border-blue-200 dark:border-gray-700 rounded-lg p-5">
                    <h2 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">
                        Message from Admin
                    </h2>

                    <p class="text-gray-800 dark:text-gray-100">
                        {{ $latestNote->note }}
                    </p>

                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        {{ ucfirst($latestNote->status) }}
                        · {{ $latestNote->created_at->format('M d, Y') }}
                    </div>
                </div>
            @endif
        @endunlessrole

        {{-- ADMIN STATUS PANEL --}}
        @role(['admin', 'superadmin'])
            @if ($order->payment)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">

                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        Order Status Review
                    </h2>

                    {{-- STATUS UPDATE FORM --}}
                    <form method="POST" action="{{ route('orders.update', $order) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium mb-1">Select Status</label>
                            <select name="status"
                                class="w-full p-2 rounded-md border bg-white text-gray-900 border-gray-300
                                       dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" @selected($order->payment->status === 'pending')>Pending</option>
                                <option value="approved" @selected($order->payment->status === 'approved')>Approved</option>
                                <option value="rejected" @selected($order->payment->status === 'rejected')>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Notes (Required)</label>
                            <textarea name="note" rows="4" required
                                placeholder="Explain clearly why this status was set. This will be visible to the client."
                                class="w-full p-3 rounded-md border bg-white text-gray-900 border-gray-300
                                       dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600
                                       focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <button class="w-full py-2 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700">
                            Save Status & Notes
                        </button>
                    </form>

                    {{-- STATUS HISTORY TIMELINE --}}
                    @php
                        $notes = \App\Models\TransactionStatusNote::with('user')
                            ->where('transaction_id', $order->payment->id)
                            ->latest()
                            ->get();
                    @endphp

                    @if ($notes->count())
                        <div class="border-t pt-4 space-y-4">
                            <h3 class="text-md font-semibold">Status History</h3>

                            @foreach ($notes as $note)
                                <div class="flex gap-3 items-start">
                                    <div
                                        class="w-3 h-3 mt-1 rounded-full
                                        @if ($note->status === 'approved') bg-green-500
                                        @elseif ($note->status === 'pending') bg-yellow-400
                                        @else bg-red-500 @endif">
                                    </div>

                                    <div class="flex-1 p-3 rounded-md bg-gray-50 dark:bg-gray-700 border">
                                        <div class="flex justify-between text-sm font-semibold">
                                            <span>{{ strtoupper($note->status) }}</span>
                                            <span class="text-xs text-gray-500">
                                                {{ $note->created_at->format('Y-m-d') }}
                                            </span>
                                        </div>

                                        <p class="text-sm mt-2">
                                            {{ $note->note }}
                                        </p>

                                        <p class="text-xs text-gray-500 mt-2">
                                            Checked by {{ $note->user?->name }}
                                            @if ($note->user)
                                                ({{ $note->user->getRoleNames()->implode(', ') }})
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endif
        @endrole

    </div>
@endsection
