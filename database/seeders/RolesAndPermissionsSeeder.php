<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa cache roles/permissions (tránh lỗi lặp)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tạo permissions
        $permissions = [
            'view products',
            'manage products',
            'view users',
            'manage users',
            'manage orders',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Tạo roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $user  = Role::firstOrCreate(['name' => 'user']);

        // Gán quyền cho vai trò
        $admin->syncPermissions($permissions); // admin có tất cả quyền
        $staff->syncPermissions(['view products', 'manage products', 'manage orders']);
        $user->syncPermissions(['view products']); // user chỉ xem sản phẩm

        // Gán role cho user cụ thể (ví dụ bạn có user email: nhien1@gmail.com)
        $userModel = User::where('email', 'nhien12345@gmail.com')->first();
        if ($userModel) {
            $userModel->assignRole('admin'); // gán vai trò admin
        }

        $staff = User::where('email', 'staff1@gmail.com')->first();
        if ($staff) {
            $staff->assignRole('staff');
        }

        $user = User::where('email', 'user1@gmail.com')->first();
        if ($user) {
            $user->assignRole('user');
        }
    }
}
