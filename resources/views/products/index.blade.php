@extends('layouts.app')

@section('content')
    <div class="space-y-8">

        <!-- Header + Search -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Products</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Browse and manage your product inventory.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Search Bar -->
                <form method="GET" action="{{ route('products.index') }}" class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or SKU..."
                        class="w-full pl-10 pr-10 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>

                    @if (request('search'))
                        <a href="{{ route('products.index') }}"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 text-lg font-bold">
                            &times;
                        </a>
                    @endif
                </form>

                @can('products.create')
                    <a href="{{ route('products.create') }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow">
                        + Add Product
                    </a>
                @endcan
            </div>
        </div>

        <!-- Mobile View (Cards) -->
        <div class="space-y-4 md:hidden">
            @foreach ($products as $product)
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow space-y-4">
                    <div class="flex gap-4 items-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="h-16 w-16 object-cover rounded-lg border border-gray-300 dark:border-gray-700">
                        @endif
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-gray-800 dark:text-white">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Category:</strong> {{ $product->category ?? '—' }}
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                        <div><span class="text-gray-500">Price</span><br>₱{{ number_format($product->price, 2) }}</div>
                        <div><span class="text-gray-500">Stock</span><br>{{ $product->stock_quantity }}</div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                        @if (auth()->user()->hasRole(['client', 'admin', 'superadmin']))
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-1">
                                @csrf
                                <button
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg transition"
                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                    Add to Cart
                                </button>
                            </form>
                        @endif

                        @if (auth()->user()->hasRole(['admin', 'superadmin']))
                            <a href="{{ route('products.edit', $product) }}"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-center transition">
                                Edit
                            </a>
                        @endif

                        @if (auth()->user()->hasRole('superadmin'))
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this)"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Name</th>
                        <th class="px-6 py-3 text-left font-medium">SKU</th>
                        <th class="px-6 py-3 text-left font-medium">Category</th>
                        <th class="px-6 py-3 text-right font-medium">Price</th>
                        <th class="px-6 py-3 text-center font-medium">Stock</th>
                        <th class="px-6 py-3 text-center font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="h-10 w-10 object-cover rounded-lg border border-gray-300 dark:border-gray-700">
                                @endif
                                <span class="font-medium text-gray-800 dark:text-white">{{ $product->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $product->sku }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $product->category ?? '—' }}</td>
                            <td class="px-6 py-4 text-right text-gray-800 dark:text-gray-100">
                                ₱{{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                {{ $product->stock_quantity > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex gap-2">
                                    @if (auth()->user()->hasRole(['client', 'admin', 'superadmin']))
                                        <form method="POST" action="{{ route('cart.add', $product) }}">
                                            @csrf
                                            <button
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded shadow-sm text-sm"
                                                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                Add
                                            </button>
                                        </form>
                                    @endif

                                    @if (auth()->user()->hasRole(['admin', 'superadmin']))
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow-sm text-sm">
                                            Edit
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasRole('superadmin'))
                                        <form method="POST" action="{{ route('products.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow-sm text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- DELETE CONFIRMATION -->
    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This product will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
@endsection
