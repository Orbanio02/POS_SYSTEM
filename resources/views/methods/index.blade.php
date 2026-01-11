@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 text-gray-900 dark:text-gray-100">

        {{-- PAYMENT INSTRUCTIONS (unchanged logic, improved spacing only) --}}
        @role('superadmin')
            <div class="space-y-4">
                <h2 class="text-xl font-semibold mt-6">Payment Instructions</h2>

                <div class="space-y-4">
                    @foreach ($methods as $method)
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 space-y-3">
                            <div class="font-semibold text-lg">
                                {{ $method->name }}
                            </div>

                            <form method="POST" action="{{ route('methods.instructions', $method) }}" class="space-y-3">
                                @csrf
                                @method('PATCH')

                                <textarea name="instructions" rows="4"
                                    class="w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 px-3 py-2
                                       focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $method->instructions }}</textarea>

                                <div class="flex justify-end">
                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 transition
                                           text-white px-4 py-2 rounded-md font-semibold">
                                        Save Instructions
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endrole

        {{-- BANK ACCOUNTS --}}
        <div class="space-y-4">
            <h2 class="text-xl font-semibold mt-6">Bank Accounts (Bank Transfer)</h2>

            {{-- ADD BANK ACCOUNT --}}
            @role('superadmin')
                <form method="POST" action="{{ route('bank-accounts.store') }}"
                    class="grid gap-3 md:grid-cols-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    @csrf

                    <input name="bank_name" required placeholder="Bank Name"
                        class="rounded-md border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-green-500">

                    <input name="account_name" required placeholder="Account Name"
                        class="rounded-md border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-green-500">

                    <input name="account_number" required placeholder="Account Number"
                        class="rounded-md border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-green-500">

                    <input name="branch" placeholder="Branch (optional)"
                        class="rounded-md border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-green-500">

                    <div class="md:col-span-4 flex justify-end">
                        <button
                            class="bg-green-600 hover:bg-green-700 transition
                               text-white px-4 py-2 rounded-md font-semibold">
                            Add Bank Account
                        </button>
                    </div>
                </form>
            @endrole

            {{-- BANK LIST --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Bank</th>
                            <th class="px-4 py-3 text-left">Account Name</th>
                            <th class="px-4 py-3 text-left">Account Number</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Branch</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($bankAccounts as $bank)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 font-medium">{{ $bank->bank_name }}</td>
                                <td class="px-4 py-3">{{ $bank->account_name }}</td>
                                <td class="px-4 py-3 text-xs font-mono">{{ $bank->account_number }}</td>
                                <td class="px-4 py-3 hidden md:table-cell">{{ $bank->branch ?? '—' }}</td>

                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $bank->is_active
                                        ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100'
                                        : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $bank->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @role('superadmin')
                                        <div class="inline-flex items-center gap-2">

                                            {{-- EDIT --}}
                                            <button onclick="openEditModal({{ $bank }})"
                                                class="px-3 py-1 rounded text-xs
                                               bg-blue-600 hover:bg-blue-700 transition text-white">
                                                Edit
                                            </button>

                                            {{-- TOGGLE --}}
                                            <form method="POST" action="{{ route('bank-accounts.toggle', $bank) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="px-3 py-1 rounded text-xs
                                            {{ $bank->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }}
                                            transition text-white">
                                                    {{ $bank->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>

                                            {{-- DELETE --}}
                                            <form method="POST" action="{{ route('bank-accounts.destroy', $bank) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)"
                                                    class="px-3 py-1 rounded text-xs
                                                   bg-red-600 hover:bg-red-700 transition text-white">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    @endrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full max-w-md shadow-lg">
            <h3 class="text-lg font-semibold mb-4">Edit Bank Account</h3>

            <form method="POST" id="editForm" class="space-y-3">
                @csrf
                @method('PATCH')

                <input id="edit_bank_name" name="bank_name"
                    class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                <input id="edit_account_name" name="account_name"
                    class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                <input id="edit_account_number" name="account_number"
                    class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                <input id="edit_branch" name="branch"
                    class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded bg-gray-500 hover:bg-gray-600 text-white">
                        Cancel
                    </button>
                    <button class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(bank) {
            document.getElementById('editForm').action = `/bank-accounts/${bank.id}`;
            document.getElementById('edit_bank_name').value = bank.bank_name;
            document.getElementById('edit_account_name').value = bank.account_name;
            document.getElementById('edit_account_number').value = bank.account_number;
            document.getElementById('edit_branch').value = bank.branch ?? '';
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection
