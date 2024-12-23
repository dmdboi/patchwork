<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminUser = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

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

        // Create role, assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Assign role to user
        $adminUser->assignRole($adminRole);

        // Collection
        Collection::firstOrCreate([
            'slug' => 'blog',
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'description' => 'My blog',
            ]
        ]);

        // Menu
        Menu::firstOrCreate([
            'slug' => 'main',
        ], [
            'name' => 'Main',
            'slug' => 'main',
            'description' => 'Main menu',
        ]);
    }
}
