@extends('layouts.app')

@section('content')

    <div
        class="max-w-xl mx-auto
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
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" required
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                          focus:ring-2 focus:ring-blue-500 focus:outline-none p-2">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input name="email" type="email" required
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                          focus:ring-2 focus:ring-blue-500 focus:outline-none p-2">
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <div class="relative">
                    <input name="password" type="password" required minlength="8"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:outline-none p-2 pr-10">
                    <button type="button"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2
                               text-gray-500 hover:text-gray-700 dark:hover:text-gray-300
                               toggle-password"
                        data-target="0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                                         9.542 7-1.274 4.057-5.064 7-9.542 7-4.477
                                                         0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div>
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <div class="relative">
                    <input name="password_confirmation" type="password" required minlength="8"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:outline-none p-2 pr-10">
                    <button type="button"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2
                               text-gray-500 hover:text-gray-700 dark:hover:text-gray-300
                               toggle-password"
                        data-target="1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                                         9.542 7-1.274 4.057-5.064 7-9.542 7-4.477
                                                         0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const toggleButtons = document.querySelectorAll('.toggle-password');
                    const passwordInputs = document.querySelectorAll(
                        'input[name="password"], input[name="password_confirmation"]'
                    );

                    toggleButtons.forEach(button => {
                        button.addEventListener('click', (e) => {
                            e.preventDefault();
                            const index = button.getAttribute('data-target');
                            const input = passwordInputs[index];
                            input.type = input.type === 'password' ? 'text' : 'password';
                        });
                    });
                });
            </script>

            <!-- ROLE -->
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-blue-500 focus:outline-none p-2">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- PRODUCT ACCESS --}}
            @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                <div id="product-access" style="display:none" class="space-y-2">

                    <label class="block text-sm font-medium">
                        Product Access (Client Only)
                    </label>

                    <!-- SELECTED PRODUCTS (CHIPS) -->
                    <div id="selected-products" class="flex flex-wrap gap-2"></div>

                    <!-- SEARCH -->
                    <input type="text" id="product-search" placeholder="Search product name or SKU..."
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                               focus:ring-2 focus:ring-blue-500 p-2">

                    <!-- PRODUCT LIST -->
                    <div id="product-list" class="max-h-48 overflow-y-auto space-y-1">
                        @foreach ($products as $product)
                            <label class="flex items-center gap-2 product-item">
                                <input type="checkbox" name="products[]" value="{{ $product->id }}">
                                <span class="product-text">
                                    {{ $product->name }} ({{ $product->sku }})
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- ROLE TOGGLE SCRIPT (UNCHANGED) -->
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const roleSelect = document.querySelector('select[name="role"]');
                    const accessBox = document.getElementById('product-access');

                    if (!roleSelect || !accessBox) return;

                    const toggle = () => {
                        accessBox.style.display =
                            roleSelect.value === 'client' ? 'block' : 'none';
                    };

                    toggle();
                    roleSelect.addEventListener('change', toggle);
                });
            </script>

            <!-- SEARCH + CHIPS LOGIC -->
            <script>
                document.addEventListener('DOMContentLoaded', () => {

                    const searchInput = document.getElementById('product-search');
                    const items = document.querySelectorAll('.product-item');
                    const selectedContainer = document.getElementById('selected-products');
                    const checkboxes = document.querySelectorAll('input[name="products[]"]');

                    if (!searchInput || !selectedContainer) return;

                    searchInput.addEventListener('keyup', () => {
                        const value = searchInput.value.toLowerCase();
                        items.forEach(item => {
                            item.style.display =
                                item.innerText.toLowerCase().includes(value) ?
                                'flex' :
                                'none';
                        });
                    });

                    function createChip(checkbox) {
                        const label = checkbox.closest('label').innerText.trim();

                        const chip = document.createElement('span');
                        chip.className =
                            'flex items-center gap-1 bg-blue-100 dark:bg-blue-600 ' +
                            'text-blue-800 dark:text-white text-xs px-2 py-1 rounded';
                        chip.dataset.id = checkbox.value;

                        chip.innerHTML = `
                            ${label}
                            <button type="button" class="ml-1 font-bold">✕</button>
                        `;

                        chip.querySelector('button').addEventListener('click', () => {
                            checkbox.checked = false;
                            chip.remove();
                        });

                        selectedContainer.appendChild(chip);
                    }

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', () => {
                            const existing = selectedContainer.querySelector(
                                `[data-id="${cb.value}"]`
                            );

                            if (cb.checked && !existing) {
                                createChip(cb);
                            }

                            if (!cb.checked && existing) {
                                existing.remove();
                            }
                        });
                    });
                });
            </script>

            <!-- ACTIONS -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
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
