<section class="space-y-6">
    <header>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('delete')

            <p class="text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its data will be permanently removed.
            </p>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password
                </label>
                <input type="password" name="password" required
                    class="w-full rounded-md
                   bg-white dark:bg-gray-700
                   text-gray-900 dark:text-gray-100
                   border border-gray-300 dark:border-gray-600
                   focus:ring-2 focus:ring-red-500
                   focus:outline-none p-2">
            </div>

            <button onclick="return confirm('Are you sure you want to delete your account?')"
                class="bg-red-600 dark:bg-red-500
               text-white px-4 py-2 rounded
               hover:bg-red-700 dark:hover:bg-red-600">
                Delete Account
            </button>
        </form>

    </x-modal>
</section>
