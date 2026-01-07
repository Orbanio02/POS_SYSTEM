<!DOCTYPE html>
<html lang="en" x-data="layout()" x-init="initTheme()" x-cloak :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <title>POS System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ FIX 1: Apply theme BEFORE page paints (prevents flash on all devices) --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- ✅ FIX 2: Hide content until Alpine is ready (prevents flicker) --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="h-screen overflow-hidden bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">

    <header class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden text-xl">☰</button>
            <h1 class="text-lg font-extrabold">POS System</h1>
        </div>

        {{-- DESKTOP ONLY --}}
        <div class="hidden lg:flex items-center gap-4">

            <!-- 🌞🌙 THEME TOGGLE -->
            <button @click="toggleDarkMode"
                class="w-9 h-9 flex items-center justify-center rounded-full
                       hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                title="Toggle Theme">

                <!-- SUN (LIGHT MODE) -->
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5" />
                    <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42
                             M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                </svg>

                <!-- MOON (DARK MODE) -->
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1111.21 3
                             7 7 0 0021 12.79z" />
                </svg>
            </button>

            {{-- PROFILE ICON --}}
            <a href="{{ route('profile.edit') }}"
                class="flex items-center justify-center
                  w-9 h-9 rounded-full
                  text-gray-600 dark:text-gray-300
                  hover:bg-gray-200 dark:hover:bg-gray-700
                  transition"
                title="Profile">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="px-4 py-2
                               bg-red-600 dark:bg-red-500
                               text-white rounded
                               hover:bg-red-700 dark:hover:bg-red-600
                               transition">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="flex h-[calc(100vh-64px)]">

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <aside
            class="fixed lg:static inset-y-0 left-0 z-50 w-64
               bg-gray-200 dark:bg-gray-800 p-4
               transform transition-transform duration-300
               lg:translate-x-0"
            :class="{ '-translate-x-full': !sidebarOpen }">

            <nav class="space-y-2 text-sm">

                {{-- Dashboard --}}
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="font-bold">
                    <span class="inline-flex items-center gap-2">
                        {{-- Dashboard Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h7v9H3z"></path>
                            <path d="M14 3h7v5h-7z"></path>
                            <path d="M14 10h7v11h-7z"></path>
                            <path d="M3 14h7v7H3z"></path>
                        </svg>
                        Dashboard
                    </span>
                </x-nav-link>

                <!-- PRODUCTS (VISIBLE TO ALL) -->
                <div x-data="{ open: {{ request()->routeIs('orders.*', 'products.*', 'cart.*', 'payments.history') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                        <span class="inline-flex items-center gap-2 font-bold">
                            {{-- Products Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                </path>
                                <path d="M3.3 7l8.7 5 8.7-5"></path>
                                <path d="M12 22V12"></path>
                            </svg>
                            Products
                        </span>
                        <span x-text="open ? '−' : '+'"></span>
                    </button>

                    <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                        <x-nav-link href="{{ route('cart.index') }}" :active="request()->routeIs('cart.*')">
                            <span class="inline-flex items-center gap-2">
                                {{-- Cart Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1"></circle>
                                    <circle cx="19" cy="21" r="1"></circle>
                                    <path d="M2 2h3l2.4 12.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"></path>
                                </svg>
                                Carts
                            </span>
                        </x-nav-link>

                        <x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.*')">
                            <span class="inline-flex items-center gap-2">
                                {{-- Orders Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2">
                                    </path>
                                    <path d="M9 5a3 3 0 0 1 6 0"></path>
                                    <path d="M9 12h6"></path>
                                    <path d="M9 16h6"></path>
                                </svg>
                                Orders
                            </span>
                        </x-nav-link>

                        {{-- ✅ NEW: Client Payment History (clients only / non-admin only) --}}
                        @if (!auth()->user()->hasAnyRole(['admin', 'superadmin']))
                            <x-nav-link href="{{ route('payments.history') }}" :active="request()->routeIs('payments.history')">
                                <span class="inline-flex items-center gap-2">
                                    {{-- Payment History Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <path d="M3 10h18"></path>
                                        <path d="M7 15h2"></path>
                                        <path d="M11 15h6"></path>
                                    </svg>
                                    Payment History
                                </span>
                            </x-nav-link>
                        @endif

                        <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                            <span class="inline-flex items-center gap-2">
                                {{-- Products List Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M8 6h13"></path>
                                    <path d="M8 12h13"></path>
                                    <path d="M8 18h13"></path>
                                    <path d="M3 6h.01"></path>
                                    <path d="M3 12h.01"></path>
                                    <path d="M3 18h.01"></path>
                                </svg>
                                Products
                            </span>
                        </x-nav-link>
                    </div>
                </div>

                <!-- PARTIES (ADMIN + SUPERADMIN ONLY) -->
                @can('users.manage')
                    <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                            <span class="inline-flex items-center gap-2 font-bold">
                                {{-- Parties Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Parties
                            </span>
                            <span x-text="open ? '−' : '+'"></span>
                        </button>

                        <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">

                            {{-- ✅ SUPERADMIN ONLY: Users List --}}
                            @role('superadmin')
                                <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index') ||
                                    request()->routeIs('users.edit') ||
                                    request()->routeIs('users.show')">
                                    <span class="inline-flex items-center gap-2">
                                        {{-- Users List Icon --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M8 21h8"></path>
                                            <path d="M12 17v4"></path>
                                            <path d="M7 4h10"></path>
                                            <path d="M7 8h10"></path>
                                            <path d="M7 12h10"></path>
                                            <rect x="4" y="3" width="16" height="14" rx="2"></rect>
                                        </svg>
                                        Users List
                                    </span>
                                </x-nav-link>
                            @endrole

                            {{-- ✅ ADMIN + SUPERADMIN: Create Users --}}
                            @hasanyrole('admin|superadmin')
                                <x-nav-link href="{{ route('users.create') }}" :active="request()->routeIs('users.create')">
                                    <span class="inline-flex items-center gap-2">
                                        {{-- Create User Icon --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <line x1="20" y1="8" x2="20" y2="14"></line>
                                            <line x1="17" y1="11" x2="23" y2="11"></line>
                                        </svg>
                                        Create Users
                                    </span>
                                </x-nav-link>
                            @endhasanyrole

                        </div>
                    </div>
                @endcan

                <!-- MANAGEMENTS (ADMIN + SUPERADMIN ONLY) -->
                @canany(['inventory.view', 'payments.index'])
                    <div x-data="{ open: {{ request()->routeIs('inventory.*', 'payments.*', 'methods.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                            <span class="inline-flex items-center gap-2 font-bold">
                                {{-- Managements Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 1v2"></path>
                                    <path d="M12 21v2"></path>
                                    <path d="M4.22 4.22l1.42 1.42"></path>
                                    <path d="M18.36 18.36l1.42 1.42"></path>
                                    <path d="M1 12h2"></path>
                                    <path d="M21 12h2"></path>
                                    <path d="M4.22 19.78l1.42-1.42"></path>
                                    <path d="M18.36 5.64l1.42-1.42"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Managements
                            </span>
                            <span x-text="open ? '−' : '+'"></span>
                        </button>

                        <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                            @can('inventory.view')
                                <x-nav-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.*')">
                                    <span class="inline-flex items-center gap-2">
                                        {{-- Inventory Icon --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M20 7h-9"></path>
                                            <path d="M14 17H5"></path>
                                            <circle cx="17" cy="17" r="3"></circle>
                                            <circle cx="7" cy="7" r="3"></circle>
                                        </svg>
                                        Inventory
                                    </span>
                                </x-nav-link>
                            @endcan

                            @can('payments.index')
                                <x-nav-link href="{{ route('payments.index') }}" :active="request()->routeIs('payments.*')">
                                    <span class="inline-flex items-center gap-2">
                                        {{-- Payment History Icon --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                            <path d="M3 10h18"></path>
                                            <path d="M7 15h2"></path>
                                            <path d="M11 15h6"></path>
                                        </svg>
                                        Payment History
                                    </span>
                                </x-nav-link>
                            @endcan

                            @can('settings.manage')
                                <x-nav-link href="{{ route('methods.index') }}" :active="request()->routeIs('methods.*')">
                                    <span class="inline-flex items-center gap-2">
                                        {{-- Payment Methods Icon --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M12 20h9"></path>
                                            <path d="M12 4h9"></path>
                                            <path d="M4 9h16"></path>
                                            <path d="M4 15h16"></path>
                                            <path d="M6 7l-2 2 2 2"></path>
                                            <path d="M6 13l-2 2 2 2"></path>
                                        </svg>
                                        Payment Methods
                                    </span>
                                </x-nav-link>
                            @endcan
                        </div>
                    </div>
                @endcanany

            </nav>

            {{-- ✅ MOBILE/TABLET ONLY: Header actions moved into sidebar --}}
            <div class="mt-6 pt-4 border-t border-gray-300 dark:border-gray-700 space-y-2 lg:hidden">

                {{-- Dark Mode --}}
                <div class="px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                    <x-theme-toggle />
                </div>

                {{-- Profile --}}
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700 text-blue-600 dark:text-blue-400">
                    {{-- Profile Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Profile
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700 text-red-600 dark:text-red-400">
                        {{-- Logout Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>
                        Logout
                    </button>
                </form>

            </div>

        </aside>

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <script>
        function layout() {
            return {
                sidebarOpen: false,

                // ✅ FIX 3: Start from the actual html class (works for all devices)
                darkMode: document.documentElement.classList.contains('dark'),

                // ✅ Keep Alpine synced with real html class
                initTheme() {
                    this.darkMode = document.documentElement.classList.contains('dark');
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                }
            }
        }
    </script>

    {{-- ✅ FIX 4: Sync localStorage even when theme is toggled from mobile/tablet component --}}
    <script>
        (function() {
            const html = document.documentElement;

            const syncThemeStorage = () => {
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            };

            // Initial sync (in case something toggled class without storage)
            syncThemeStorage();

            // Watch for class changes (works no matter which toggle is used)
            const observer = new MutationObserver(() => syncThemeStorage());
            observer.observe(html, { attributes: true, attributeFilter: ['class'] });
        })();
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: @json(session('success')),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: @json(session('error')),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif
        });
    </script>

</body>

</html>
