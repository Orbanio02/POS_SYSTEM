@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->merge([
        'class' =>
            'block px-3 py-2 rounded transition ' .
            ($active
                ? 'bg-white dark:bg-gray-700 text-blue-600 font-semibold shadow'
                : 'text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700')
   ]) }}>
    {{ $slot }}
</a>
