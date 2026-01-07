@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            shadow rounded-lg p-6 space-y-6">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold">
        Create Product
    </h1>

    <!-- VALIDATION ERRORS -->
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900
                    text-red-700 dark:text-red-200
                    p-3 rounded">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <form method="POST"
          action="{{ route('products.store') }}"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf

        <!-- NAME -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Product Name
            </label>
            <input
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">
        </div>

        <!-- SKU -->
        <div>
            <label class="block text-sm font-medium mb-1">
                SKU
            </label>
            <input
                name="sku"
                value="{{ old('sku') }}"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">
        </div>

        <!-- CATEGORY -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Category
            </label>
            <textarea
                name="category"
                rows="3"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">{{ old('category') }}</textarea>
        </div>

        <!-- PRICE -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Price
            </label>
            <input
                name="price"
                type="number"
                step="0.01"
                value="{{ old('price') }}"
                required
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">
        </div>

        <!-- STOCK -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Stock Quantity
            </label>
            <input
                name="stock_quantity"
                type="number"
                value="{{ old('stock_quantity') }}"
                required
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">
        </div>

        <!-- LOW STOCK THRESHOLD -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Low Stock Threshold
            </label>
            <input
                name="low_stock_threshold"
                type="number"
                min="0"
                value="{{ old('low_stock_threshold', 5) }}"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">
        </div>

        <!-- IMAGE -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Product Image
            </label>
            <input
                type="file"
                name="image"
                accept="image/*"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-green-500
                       focus:outline-none p-2">
        </div>

        <!-- ACTIONS -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                class="bg-green-600 dark:bg-green-700
                       text-white px-4 py-2 rounded-md
                       hover:bg-green-700 dark:hover:bg-green-600">
                Save
            </button>

            <a href="{{ route('products.index') }}"
               class="bg-gray-500 dark:bg-gray-600
                      text-white px-4 py-2 rounded-md
                      hover:bg-gray-600 dark:hover:bg-gray-500">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
