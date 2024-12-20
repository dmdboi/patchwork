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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define actions and resources
        $actions = ['view', 'create', 'edit', 'delete', 'full_access'];
        $resources = ['categories', 'forms', 'menus', 'posts', 'roles', 'users', 'settings'];

        // Create permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}:{$resource}"]);
            }
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        $editorRole->givePermissionTo(['view:posts', 'create:posts', 'edit:posts', 'delete:posts']);

        // Assign roles to users
        $admin = User::where('email', env('FILAMENT_ADMIN_EMAIL'))->first();
        $admin->assignRole($adminRole);
    }
}
