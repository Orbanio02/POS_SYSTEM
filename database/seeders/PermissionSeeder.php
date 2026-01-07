<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // PRODUCTS
            'products.index',
            'products.create',
            'products.edit',
            'products.delete',

            // CART
            'cart.add',

            // ORDERS
            'orders.index',
            'orders.create',
            'orders.view',
            'orders.edit',
            'orders.delete',

            // PAYMENTS
            'payments.index',
            'payments.create',
            'payments.approve',
            'payments.reject',
            'payments.refund',

            // INVENTORY
            'inventory.adjust',
            'inventory.view',

            // USERS / SYSTEM
            'users.manage',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $client     = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // SUPERADMIN → everything
        $superadmin->syncPermissions(Permission::all());

        // ADMIN → add to cart + edit + can access Parties (create only via routes)
        $admin->syncPermissions([
            'products.index',
            'products.create',
            'products.edit',
            'cart.add',

            'orders.index',
            'orders.create',
            'orders.view',
            'orders.edit',

            'payments.index',
            'payments.create',

            'inventory.view',
            'inventory.adjust',

            // ✅ ADD THIS so sidebar can show Parties
            'users.manage',
        ]);

        // CLIENT → add to cart ONLY
        $client->syncPermissions([
            'products.index',
            'cart.add',

            'orders.create',
            'orders.view',
            'payments.create',
        ]);
    }
}
