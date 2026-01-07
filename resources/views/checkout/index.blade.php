@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 text-gray-900 dark:text-gray-100">

    <h1 class="text-2xl font-bold">Checkout</h1>

    {{-- ORDER SUMMARY --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 font-semibold border-b dark:border-gray-700">
            Order Summary
        </div>

        <table class="w-full text-sm">
            <tbody class="divide-y dark:divide-gray-700">
                @foreach ($cart as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $item['name'] }}</div>
                            <div class="text-xs text-gray-500">
                                ₱{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">
                            ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-between px-6 py-4 bg-gray-50 dark:bg-gray-700 font-bold">
            <span>Total</span>
            <span class="text-green-600 dark:text-green-400">
                ₱{{ number_format($total, 2) }}
            </span>
        </div>
    </div>

    {{-- PAYMENT --}}
    <form method="POST" action="{{ route('checkout.store') }}"
          class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
        @csrf

        <h2 class="text-lg font-semibold">Payment Method</h2>

        <select name="payment_method"
                required
                class="w-full p-2 rounded border
                       border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700">
            @foreach ($methods as $method)
                <option value="{{ $method->name }}">
                    {{ $method->name }}
                </option>
            @endforeach
        </select>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('cart.index') }}"
               class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600">
                Cancel
            </a>

            <button
                class="px-6 py-2 rounded bg-green-600 text-white font-semibold
                       hover:bg-green-700">
                Confirm & Pay
            </button>
        </div>
    </form>

</div>
@endsection
