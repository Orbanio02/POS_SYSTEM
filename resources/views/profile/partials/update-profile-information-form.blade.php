<section>
    <header>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- EMAIL VERIFICATION REMOVED (NO LONGER USED) --}}

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Name
            </label>
            <input
                name="name"
                value="{{ old('name', $user->name) }}"
                required
                class="w-full rounded-md
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       border border-gray-300 dark:border-gray-600
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email
            </label>
            <input
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                class="w-full rounded-md
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       border border-gray-300 dark:border-gray-600
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

        <div class="flex gap-3">
            <button
            type="submit"
            class="bg-blue-600 dark:bg-blue-500
                   text-white px-4 py-2 rounded
                   hover:bg-blue-700 dark:hover:bg-blue-600">
            Save
            </button>
            <a
            href="{{ route('dashboard') }}"
            class="bg-gray-600 dark:bg-gray-500
                   text-white px-4 py-2 rounded
                   hover:bg-gray-700 dark:hover:bg-gray-600
                   inline-flex items-center">
            Back
            </a>
        </div>

    
    </form>
</section>
