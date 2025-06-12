<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $roles = ['admin', 'editor', 'viewer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear permisos opcionales
        $permissions = ['manage users', 'edit content', 'view content'];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        Role::findByName('admin')->syncPermissions($permissions);
        Role::findByName('editor')->syncPermissions(['edit content', 'view content']);
        Role::findByName('viewer')->syncPermissions(['view content']);

        // Crear usuarios con sus respectivos roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin'),
            ]
        );
        $admin->assignRole('admin');

        $editor = User::firstOrCreate(
            ['email' => 'editor@editor.com'],
            [
                'name' => 'Editor',
                'password' => bcrypt('editor'),
            ]
        );
        $editor->assignRole('editor');

        $viewer = User::firstOrCreate(
            ['email' => 'viewer@viewer.com'],
            [
                'name' => 'Viewer',
                'password' => bcrypt('viewer'),
            ]
        );
        $viewer->assignRole('viewer');
    }
}
