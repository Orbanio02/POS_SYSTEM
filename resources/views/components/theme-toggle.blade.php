<button
    @click="toggleDarkMode"
    class="relative inline-flex h-6 w-11 items-center rounded-full
           bg-gray-300 dark:bg-gray-600 transition"
>
    <span
        :class="darkMode ? 'translate-x-6' : 'translate-x-1'"
        class="inline-block h-4 w-4 transform rounded-full
               bg-white transition"
    ></span>
</button>
