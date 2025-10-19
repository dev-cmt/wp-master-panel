<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // -------------------------------
        // 1️⃣ Reset Roles & Permissions (keep users)
        // php artisan db:seed --class=RolePermissionSeeder
        // -------------------------------
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // -------------------------------
        // 2️⃣ Define Modules
        // -------------------------------
        $modules = [
            'packages',
            'developer api',
            'settings',
            'seo',
            'users',
            'roles',
            'dashboard',
        ];

        // -------------------------------
        // 3️⃣ Create Permissions
        // -------------------------------
        $permissions = [];
        foreach ($modules as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                $permissionName = "{$action} {$module}";
                Permission::firstOrCreate(['name' => $permissionName]);
                $permissions[] = $permissionName;
            }
        }

        // -------------------------------
        // 4️⃣ Add Single Custom Permission
        // -------------------------------
        $customPermissions = [
            'view reports'
        ];
        foreach ($customPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
            $permissions[] = $perm;
        }

        // -------------------------------
        // 5️⃣ Create Roles
        // -------------------------------
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // -------------------------------
        // 6️⃣ Assign Permissions
        // -------------------------------
        $superAdminRole->syncPermissions($permissions); // Superadmin: all permissions
        $adminRole->syncPermissions($permissions);      // Admin: all permissions

        // Customer: only view developer api & dashboard
        $customerPermissions = array_filter($permissions, function($p) {
            return str_starts_with($p, 'view') &&
                   (str_contains($p, 'developer api') || str_contains($p, 'dashboard'));
        });
        $customerRole->syncPermissions($customerPermissions);

        // -------------------------------
        // 7️⃣ Assign Roles to Default Users
        // -------------------------------
        $superAdmin = User::firstOrCreate(
            ['email' => 'super@gmail.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('super12345')]
        );
        $superAdmin->syncRoles([$superAdminRole]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => Hash::make('admin12345')]
        );
        $admin->syncRoles([$adminRole]);

        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            ['name' => 'Customer User', 'password' => Hash::make('customer12345')]
        );
        $customer->syncRoles([$customerRole]);

        $this->command->info('✅ Roles, Permissions (including reports), and Users seeded successfully!');
    }
}
