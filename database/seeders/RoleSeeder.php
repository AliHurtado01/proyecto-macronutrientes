<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Permisos
        // El enunciado dice: "Usuario puede hacer todo MENOS eliminar productos".
        // Así que creamos un permiso específico para eliminar.
        $permissionDelete = Permission::create(['name' => 'delete products']);

        // 2. Crear Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);

        // 3. Asignar permisos a roles
        // Admin tiene permiso de eliminar
        $roleAdmin->givePermissionTo($permissionDelete);

        // User NO tiene permiso (así que no se lo damos)

        // 4. Crear un Usuario Administrador de prueba
        $admin = User::create([
            'name' => 'Administrador Jefe',
            'email' => 'admin@nutritrack.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($roleAdmin);

        // 5. Crear un Usuario Normal de prueba
        $user = User::create([
            'name' => 'Usuario Estandar',
            'email' => 'user@nutritrack.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($roleUser);
    }
}
