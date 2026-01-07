<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'superadmin']);
        $admin->assignRole($role);
    }
}
