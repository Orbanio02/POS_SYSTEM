@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="text-2xl font-bold">Products</h1>

            @can('products.create')
                <a href="{{ route('products.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                    + Add Product
                </a>
            @endcan
        </div>

        <!-- Mobile Card View -->
        <div class="space-y-4 md:hidden">
            @foreach ($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded shadow p-4 space-y-2">

                    {{-- Image + Name --}}
                    <div class="flex items-center gap-3">
                        @if ($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="h-12 w-12 rounded object-cover border border-gray-200 dark:border-gray-700">
                        @endif

                        <div class="font-bold text-lg">{{ $product->name }}</div>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>

                    {{-- Category --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Category: {{ $product->category ?? '—' }}
                    </div>

                    <div class="text-sm">Price: ₱{{ number_format($product->price, 2) }}</div>
                    <div class="text-sm">Stock: {{ $product->stock_quantity }}</div>

                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                        {{-- Add to Cart --}}
                        @if (auth()->user()->hasRole(['client', 'admin', 'superadmin']))
                            <form method="POST" action="{{ route('cart.add', $product) }}">
                                @csrf
                                <button class="w-full bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700"
                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                    Add to Cart
                                </button>
                            </form>
                        @endif

                        {{-- Edit --}}
                        @if (auth()->user()->hasRole(['admin', 'superadmin']))
                            <a href="{{ route('products.edit', $product) }}"
                                class="w-full bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-center">
                                Edit
                            </a>
                        @endif

                        {{-- Delete --}}
                        @if (auth()->user()->hasRole('superadmin'))
                            <form method="POST" action="{{ route('products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this)"
                                    class="w-full bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View for Desktop & Tablet -->
        <div class="hidden md:block bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">SKU</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-right">Price</th>
                        <th class="px-4 py-3 text-center">Stock</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($products as $product)
                        <tr>
                            {{-- Image + Name --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($product->image)
                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="h-10 w-10 rounded object-cover border border-gray-200 dark:border-gray-700">
                                    @endif

                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-3">{{ $product->sku }}</td>
                            <td class="px-4 py-3">{{ $product->category ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">₱{{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3 text-center">{{ $product->stock_quantity }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex flex-wrap gap-2 justify-center">
                                    {{-- Add to Cart --}}
                                    @if (auth()->user()->hasRole(['client', 'admin', 'superadmin']))
                                        <form method="POST" action="{{ route('cart.add', $product) }}">
                                            @csrf
                                            <button class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700"
                                                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                Add to Cart
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Edit --}}
                                    @if (auth()->user()->hasRole(['admin', 'superadmin']))
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                            Edit
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    @if (auth()->user()->hasRole('superadmin'))
                                        <form method="POST" action="{{ route('products.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
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

    {{-- DELETE CONFIRMATION --}}
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
