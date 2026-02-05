<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Permissions
    $permissions = [
        'manage_admins',
        'manage_roles',
        'view_dashboard',
        'manage_orders'
    ];

    foreach ($permissions as $permission) {
        Permission::create([
            'name' => $permission,
            'guard_name' => 'admin'
        ]);
    }

    // Roles
    $superAdmin = Role::create(['name'=>'Super Admin','guard_name'=>'admin']);
    $admin      = Role::create(['name'=>'Admin','guard_name'=>'admin']);
    $staff      = Role::create(['name'=>'Staff','guard_name'=>'admin']);

    $superAdmin->givePermissionTo(Permission::all());
    $admin->givePermissionTo(['view_dashboard','manage_orders']);
    $staff->givePermissionTo(['view_dashboard']);
    }
}
