@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto space-y-6
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            shadow rounded-lg p-6">

    <h1 class="text-xl font-bold">
        Checkout Order #{{ $order->order_number }}
    </h1>

    <form method="POST"
          action="{{ route('payments.store', $order) }}"
          class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1 font-medium">
                Payment Method
            </label>

            <select name="method"
                class="w-full rounded-md border
                       border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100 p-2">
                @foreach ($methods as $method)
                    <option value="{{ $method->name }}">
                        {{ $method->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700
                   text-white px-4 py-2 rounded">
            Confirm Payment
        </button>
    </form>

</div>
@endsection
