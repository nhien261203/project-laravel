<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo permission nếu chưa có
        $editRolePermission = Permission::firstOrCreate(['name' => 'edit role']);
        $viewLogPermission  = Permission::firstOrCreate(['name' => 'view log']);

        // Gán cho vai trò admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($editRolePermission);
        $adminRole->givePermissionTo($viewLogPermission);
    }
}
