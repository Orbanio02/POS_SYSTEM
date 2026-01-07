@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-2xl font-bold">Users</h1>
    </div>

    <!-- MOBILE VIEW (CARDS) -->
    <div class="md:hidden space-y-4">
        @forelse ($users as $user)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 space-y-2">
                <div class="font-semibold text-lg">{{ $user->name }}</div>
                <div class="text-sm text-gray-500 break-words">{{ $user->email }}</div>
                <div class="text-sm">
                    <strong>Role:</strong> {{ $user->roles->pluck('name')->first() ?? '—' }}
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <a href="{{ route('users.edit', $user) }}"
                       class="w-full bg-blue-600 text-white py-2 rounded text-center hover:bg-blue-700">
                        Edit
                    </a>

                    @if ($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="confirmDelete(this)"
                                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">No users found.</p>
        @endforelse
    </div>

    <!-- DESKTOP / TABLET VIEW (TABLE) -->
    <div class="hidden md:block bg-white dark:bg-gray-800 shadow rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-center">Role</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3 break-all text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-center">{{ $user->roles->pluck('name')->first() ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col sm:flex-row justify-center gap-2">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-center hover:bg-blue-700">
                                    Edit
                                </a>

                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete(this)"
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">No users found.</td>
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
