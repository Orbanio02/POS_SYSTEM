<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="max-w-md mx-auto my-8 bg-white shadow rounded-lg p-6">

        {{-- ACTIONS --}}
        <div class="no-print flex justify-between items-center mb-4">
            <a href="{{ route('orders.show', $order) }}"
               class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 text-sm font-semibold">
                Back
            </a>

            <button onclick="window.print()"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-semibold">
                Print
            </button>
        </div>

        {{-- RECEIPT HEADER --}}
        <div class="text-center border-b pb-4 mb-4">
            <h1 class="text-lg font-extrabold">POS System</h1>
            <p class="text-xs text-gray-500">Official Receipt</p>
        </div>

        {{-- META --}}
        <div class="space-y-1 text-sm mb-4">
            <div class="flex justify-between">
                <span class="text-gray-600">Order No.</span>
                <span class="font-semibold">{{ $order->order_number }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Date</span>
                <span class="font-semibold">
                    {{ optional($order->created_at)->format('M d, Y') }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Customer</span>
                <span class="font-semibold">
                    {{ optional($order->user)->name ?? 'N/A' }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Payment Method</span>
                <span class="font-semibold">
                    {{ optional($order->payment)->method ?? 'N/A' }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Payment Status</span>
                <span class="font-semibold">
                    {{ ucfirst(optional($order->payment)->status ?? 'pending') }}
                </span>
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="border-t pt-4">
            <h2 class="font-semibold text-sm mb-2">Items</h2>

            <div class="space-y-2 text-sm">
                @foreach ($order->items as $item)
                    <div class="flex justify-between">
                        <div>
                            <div class="font-medium">
                                {{ optional($item->product)->name ?? 'Product' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                ₱{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}
                            </div>
                        </div>
                        <div class="font-semibold">
                            ₱{{ number_format($item->total_price, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="border-t mt-4 pt-4">
            <div class="flex justify-between text-base font-bold">
                <span>Total</span>
                <span class="text-green-700">₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="text-center text-xs text-gray-500 mt-6">
            Thank you for your purchase!
        </div>

    </div>

    {{-- OPTIONAL: Auto-print when opened with ?print=1 --}}
    @if(request('print') == 1)
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif

</body>
</html>
