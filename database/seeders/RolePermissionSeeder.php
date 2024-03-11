<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $data = config('default-role-permissions');
        foreach ($data as $roleData) {
            $role = Role::findOrCreate($roleData['role']);
            $permissions = [];
            foreach ($roleData['permissions'] as $permission) {
                $permission = Permission::findOrCreate($permission);
                $permissions[] = $permission;
            }
            $role->syncPermissions($permissions);
        }
    }
}
