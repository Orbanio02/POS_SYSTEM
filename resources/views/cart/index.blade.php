@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
        Cart
    </h1>

    @if (empty($cart))
        <p class="text-gray-600 dark:text-gray-400">No items in cart.</p>
    @else

    {{-- MOBILE VIEW --}}
    <form method="POST" class="md:hidden space-y-4">
        @csrf

        @foreach ($cart as $item)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 space-y-2
                        text-gray-900 dark:text-gray-100">

                <div class="font-semibold text-lg">
                    {{ $item['name'] }}
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Quantity</span>
                    <input type="number"
                           name="quantities[{{ $item['id'] }}]"
                           min="1"
                           value="{{ $item['quantity'] }}"
                           class="w-16 text-center rounded
                                  bg-white dark:bg-gray-700
                                  border border-gray-300 dark:border-gray-600
                                  text-gray-900 dark:text-gray-100">
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Price</span>
                    <span>₱{{ number_format($item['price'], 2) }}</span>
                </div>

                <div class="flex justify-between text-sm font-semibold">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </div>

                <button formaction="{{ route('cart.remove', $item['id']) }}"
                        formmethod="POST"
                        class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                    @csrf
                    Remove
                </button>
            </div>
        @endforeach

        <div class="space-y-2 text-gray-900 dark:text-gray-100">
            <div class="text-lg font-bold">
                Total: ₱{{ number_format($total, 2) }}
            </div>

            <button formaction="{{ route('cart.updateAll') }}"
                    formmethod="POST"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Update Cart
            </button>

            <a href="{{ route('checkout.index') }}"
               class="block text-center bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Checkout
            </a>
        </div>
    </form>

    {{-- DESKTOP / TABLET VIEW --}}
    <form method="POST" class="hidden md:block">
        @csrf

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow
                          text-gray-900 dark:text-gray-100">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="p-3">Product</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-center">Price</th>
                        <th class="p-3 text-center">Subtotal</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($cart as $item)
                        <tr>
                            <td class="p-3">{{ $item['name'] }}</td>

                            <td class="p-3 text-center">
                                <input type="number"
                                       name="quantities[{{ $item['id'] }}]"
                                       min="1"
                                       value="{{ $item['quantity'] }}"
                                       class="w-16 text-center rounded
                                              bg-white dark:bg-gray-700
                                              border border-gray-300 dark:border-gray-600
                                              text-gray-900 dark:text-gray-100">
                            </td>

                            <td class="p-3 text-center">
                                ₱{{ number_format($item['price'], 2) }}
                            </td>

                            <td class="p-3 text-center font-semibold">
                                ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </td>

                            <td class="p-3 text-center">
                                <button formaction="{{ route('cart.remove', $item['id']) }}"
                                        formmethod="POST"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    @csrf
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center
                    bg-gray-100 dark:bg-gray-700
                    text-gray-900 dark:text-gray-100
                    p-4 rounded mt-4">
            <strong class="text-lg">
                Total: ₱{{ number_format($total, 2) }}
            </strong>

            <div class="flex gap-3">
                <button formaction="{{ route('cart.updateAll') }}"
                        formmethod="POST"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Cart
                </button>

                <a href="{{ route('checkout.index') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Checkout
                </a>
            </div>
        </div>
    </form>

    @endif
</div>
@endsection
