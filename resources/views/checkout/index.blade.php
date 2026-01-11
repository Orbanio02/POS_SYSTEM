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

            <select name="payment_method" required
                class="w-full p-2 rounded border
                       border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700">
                @foreach ($methods as $method)
                    <option value="{{ $method->name }}">
                        {{ $method->name }}
                    </option>
                @endforeach
            </select>

            {{-- Dynamic Payment Info --}}
            <div class="mt-4 space-y-3 text-sm" id="payment-info-wrapper">

                {{-- BANK TRANSFER --}}
                <div id="payment-info-bank-transfer"
                    class="hidden border rounded-md px-4 py-3
                                                     bg-blue-50 dark:bg-blue-900/30
                                                     border-blue-200 dark:border-blue-700">
                    <div class="font-semibold mb-1">Bank Transfer Instructions</div>

                    <p class="mb-2 text-xs text-gray-600 dark:text-gray-300">
                        Please send your payment to one of the bank accounts below and keep your deposit slip or
                        transaction reference for verification.
                    </p>

                    @if ($bankAccounts->isEmpty())
                        <p class="text-xs text-red-500">
                            No bank accounts are currently configured. Please contact support.
                        </p>
                    @else
                        <div class="space-y-2">
                            @foreach ($bankAccounts as $bank)
                                <div>
                                    <div class="font-semibold">{{ $bank->bank_name }}</div>
                                    <div>Account Name: <span class="font-mono">{{ $bank->account_name }}</span></div>
                                    <div>Account Number: <span class="font-mono">{{ $bank->account_number }}</span></div>
                                    @if ($bank->branch)
                                        <div>Branch: {{ $bank->branch }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-3 text-xs text-gray-600 dark:text-gray-300">
                        @php
                            $bankTransferMethod = $methods->first(
                                fn($m) => strtolower(trim($m->name)) === 'bank transfer',
                            );
                        @endphp
                        @if ($bankTransferMethod && $bankTransferMethod->instructions)
                            <div class="mt-2 whitespace-pre-line">
                                {{ $bankTransferMethod->instructions }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CHEQUE --}}
                <div id="payment-info-cheque"
                    class="hidden border rounded-md px-4 py-3
                                                bg-yellow-50 dark:bg-yellow-900/30
                                                border-yellow-200 dark:border-yellow-700">
                    <div class="font-semibold mb-1">Cheque Payment Instructions</div>
                    <div class="text-xs text-gray-700 dark:text-gray-200 whitespace-pre-line" id="cheque-instructions">
                        {{-- filled by JS --}}
                    </div>
                </div>

                {{-- MANUAL --}}
                <div id="payment-info-manual"
                    class="hidden border rounded-md px-4 py-3
                                                bg-gray-50 dark:bg-gray-900/40
                                                border-gray-200 dark:border-gray-600">
                    <div class="font-semibold mb-1">Manual Payment (Pay at Counter)</div>
                    <div class="text-xs text-gray-700 dark:text-gray-200 whitespace-pre-line" id="manual-instructions">
                        {{-- filled by JS --}}
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('cart.index') }}" class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.querySelector('select[name="payment_method"]');
            if (!select) return;

            const bankDiv = document.getElementById('payment-info-bank-transfer');
            const chequeDiv = document.getElementById('payment-info-cheque');
            const manualDiv = document.getElementById('payment-info-manual');

            const methodInstructions = {
                @foreach ($methods as $m)
                    "{{ strtolower(trim($m->name)) }}": @json($m->instructions ?? ''),
                @endforeach
            };

            function normalize(value) {
                return (value || '').toLowerCase().trim();
            }

            function setInstructions(targetId, methodName) {
                const el = document.getElementById(targetId);
                if (!el) return;

                const key = normalize(methodName);
                const text = methodInstructions[key] || '';

                el.textContent = text || 'No instructions provided. Please contact support.';
            }

            function updatePaymentInfo() {
                const value = normalize(select.value);

                if (bankDiv) bankDiv.classList.add('hidden');
                if (chequeDiv) chequeDiv.classList.add('hidden');
                if (manualDiv) manualDiv.classList.add('hidden');

                if (['bank transfer', 'bank_transfer', 'bank-transfer'].includes(value)) {
                    if (bankDiv) bankDiv.classList.remove('hidden');
                } else if (['cheque', 'check'].includes(value)) {
                    if (chequeDiv) chequeDiv.classList.remove('hidden');
                    setInstructions('cheque-instructions', select.value);
                } else if (['manual payment', 'manual', 'cash'].includes(value)) {
                    if (manualDiv) manualDiv.classList.remove('hidden');
                    setInstructions('manual-instructions', select.value);
                }
            }

            updatePaymentInfo();
            select.addEventListener('change', updatePaymentInfo);
        });
    </script>
@endsection
