@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            shadow rounded-lg p-6 space-y-6">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold">
        Create User
    </h1>

    <!-- FORM -->
    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
        @csrf

        <!-- NAME -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Name
            </label>
            <input
                name="name"
                required
                placeholder="Name"
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
                required
                placeholder="Email"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Password
            </label>
            <input
                name="password"
                type="password"
                required
                placeholder="Password"
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <!-- CONFIRM PASSWORD -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Confirm Password
            </label>
            <input
                name="password_confirmation"
                type="password"
                required
                placeholder="Confirm Password"
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
                required
                class="w-full rounded-md
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
                @foreach ($roles as $role)
                    <option value="{{ $role }}">
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- ACTIONS -->
        <div class="flex gap-3 pt-4">
            <button
                type="submit"
                class="bg-green-600 dark:bg-green-500
                       text-white px-4 py-2 rounded-md
                       hover:bg-green-700 dark:hover:bg-green-600">
                Create
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
