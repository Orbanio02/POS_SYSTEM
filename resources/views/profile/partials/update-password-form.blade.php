<section>
    <header>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

  <form method="post" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    @method('put')

    @foreach ([
        'current_password' => 'Current Password',
        'password' => 'New Password',
        'password_confirmation' => 'Confirm Password'
    ] as $name => $label)

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ $label }}
            </label>
            <input
                type="password"
                name="{{ $name }}"
                required
                class="w-full rounded-md
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       border border-gray-300 dark:border-gray-600
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none p-2">
        </div>

    @endforeach

    <button
        class="bg-green-600 dark:bg-green-500
               text-white px-4 py-2 rounded
               hover:bg-green-700 dark:hover:bg-green-600">
        Update Password
    </button>
</form>

</section>
