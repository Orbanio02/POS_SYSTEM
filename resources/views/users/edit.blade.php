@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            shadow rounded-lg p-6 space-y-6">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold">
        Edit User
    </h1>

    <!-- FORM -->
    <form method="POST"
          action="{{ route('users.update', $user) }}"
          class="space-y-4">

        @csrf
        @method('PUT')

        <!-- NAME -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Name
            </label>
            <input
                name="name"
                value="{{ $user->name }}"
                required
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Email
            </label>
            <input
                name="email"
                type="email"
                value="{{ $user->email }}"
                required
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <!-- ROLE -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Role
            </label>
            <select
                name="role"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
                @foreach ($roles as $role)
                    <option value="{{ $role }}"
                        @selected($user->hasRole($role))>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- RESET PASSWORD (SUPERADMIN SETS NEW ONE) -->
        <div class="pt-2">
            <label class="block text-sm font-medium mb-1">
                New Password (optional)
            </label>
            <input
                name="password"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Leave blank to keep current password. (Passwords cannot be viewed for security.)
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Confirm New Password
            </label>
            <input
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <!-- ACTIONS -->
        <div class="flex gap-3 pt-4">
            <button
                type="submit"
                class="bg-blue-600 dark:bg-blue-500
                       text-white px-4 py-2 rounded-md
                       hover:bg-blue-700 dark:hover:bg-blue-600">
                Update
            </button>

            <a href="{{ route('users.index') }}"
               class="bg-gray-500 dark:bg-gray-600
                      text-white px-4 py-2 rounded-md
                      hover:bg-gray-600 dark:hover:bg-gray-500">
                Back
            </a>
        </div>

    </form>

</div>

@endsection
