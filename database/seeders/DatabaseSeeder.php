<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles must be created before permissions assignment
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
