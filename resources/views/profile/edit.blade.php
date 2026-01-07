@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- PAGE TITLE -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Profile
        </h2>

        <!-- UPDATE PROFILE INFO -->
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                Profile Information
            </h3>

            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- UPDATE PASSWORD (SUPERADMIN ONLY) -->
        @role('superadmin')
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">
                    Update Password
                </h3>

                @include('profile.partials.update-password-form')
            </div>
        @endrole

        <!-- DELETE ACCOUNT (SUPERADMIN ONLY) -->
        @role('superadmin')
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-red-600 dark:text-red-400">
                    Delete Account
                </h3>

                @include('profile.partials.delete-user-form')
            </div>
        @endrole

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let message = 'Changes saved successfully.';
                let icon = 'success';

                @if (session('status') === 'profile-updated')
                    message = 'Your profile information has been updated.';
                @elseif (session('status') === 'password-updated')
                    message = 'Your password has been updated successfully.';
                @endif

                Swal.fire({
                    icon: icon,
                    title: 'Success',
                    text: message,
                    timer: 2500,
                    showConfirmButton: false,
                    background: document.documentElement.classList.contains('dark') ?
                        '#1f2937' :
                        '#ffffff',
                    color: document.documentElement.classList.contains('dark') ?
                        '#f9fafb' :
                        '#111827'
                });
            });
        </script>
    @endif
@endsection
