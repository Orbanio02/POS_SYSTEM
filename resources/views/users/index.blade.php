@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- HEADER WITH SEARCH -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Users</h1>

            <!-- SEARCH FORM -->
            <form method="GET" action="{{ route('users.index') }}" class="relative flex items-center space-x-2">
                <input type="text" name="search" placeholder="Search by name" value="{{ request('search') }}"
                    class="w-64 px-4 py-2 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />

                @if (request('search'))
                    <a href="{{ route('users.index') }}"
                        class="absolute right-24 text-gray-500 hover:text-red-600 dark:hover:text-red-400 text-lg font-bold px-2">
                        &times;
                    </a>
                @endif

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
                    Search
                </button>
            </form>
        </div>

        <!-- MOBILE VIEW (CARDS) -->
        <div class="md:hidden space-y-4">
            @forelse ($users as $user)
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 space-y-3 border border-gray-200 dark:border-gray-700">
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300 break-words">{{ $user->email }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Role:</strong> {{ $user->roles->pluck('name')->first() ?? '—' }}
                    </div>

                    <div class="flex flex-col gap-2 pt-3">
                        <a href="{{ route('users.edit', $user) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-center font-medium">
                            Edit
                        </a>

                        @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this)"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-center font-medium">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400">No users found.</p>
            @endforelse
        </div>

        <!-- DESKTOP / TABLET VIEW (TABLE) -->
        <div
            class="hidden md:block bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 break-all">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-200">
                                {{ $user->roles->pluck('name')->first() ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm font-semibold">
                                        Edit
                                    </a>

                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow text-sm font-semibold">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">No users
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DELETE CONFIRMATION --}}
    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This user will be permanently deleted.',
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
