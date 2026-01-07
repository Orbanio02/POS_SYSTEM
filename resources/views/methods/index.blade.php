@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 text-gray-900 dark:text-gray-100">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h1 class="text-2xl font-bold">Payment Methods</h1>
        </div>

        <!-- ADD METHOD FORM -->
        <form method="POST" action="{{ route('methods.store') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf

            <input name="name" required placeholder="New payment method"
                class="flex-1 rounded-md border border-gray-300 dark:border-gray-600
                      bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                      px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                class="bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600
                       text-white px-4 py-2 rounded-md font-semibold text-center">
                Add
            </button>
        </form>

        <!-- MOBILE CARDS -->
        <div class="space-y-4 md:hidden">
            @forelse ($methods as $method)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 space-y-2">
                    <div class="font-bold text-lg">{{ $method->name }}</div>

                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                        @if ($method->is_active) bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                        @else
                            bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                            {{ $method->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <!-- TOGGLE -->
                        <form method="POST" action="{{ route('methods.toggle', $method) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full px-3 py-1 rounded text-xs font-semibold
                                    @if ($method->is_active) bg-gray-500 hover:bg-gray-600 text-white
                                    @else
                                        bg-green-600 hover:bg-green-700 text-white @endif">
                                {{ $method->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>

                        <!-- DELETE -->
                        <form method="POST" action="{{ route('methods.destroy', $method) }}"
                            class="w-full delete-method-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(this)"
                                class="w-full px-3 py-1 rounded text-xs font-semibold
                                       bg-red-600 hover:bg-red-700 text-white">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400">
                    No payment methods found.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE -->
        <div class="hidden md:block bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Method</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($methods as $method)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-medium">{{ $method->name }}</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                @if ($method->is_active) bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @else
                                    bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $method->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <!-- TOGGLE -->
                                <form method="POST" action="{{ route('methods.toggle', $method) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1 rounded text-xs font-semibold
                                            @if ($method->is_active) bg-gray-500 hover:bg-gray-600 text-white
                                            @else
                                                bg-green-600 hover:bg-green-700 text-white @endif">
                                        {{ $method->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>

                                <!-- DELETE -->
                                <form method="POST" action="{{ route('methods.destroy', $method) }}"
                                    class="inline delete-method-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)"
                                        class="px-3 py-1 rounded text-xs font-semibold
                                               bg-red-600 hover:bg-red-700 text-white">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                No payment methods found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- SUCCESS FLASH --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success',
                    text: @json(session('success')),
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        </script>
    @endif

    {{-- DELETE CONFIRMATION --}}
    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This payment method will be deleted.',
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
