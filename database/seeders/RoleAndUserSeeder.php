<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        // Crear Roles
        $adminRole = Role::create(['name' => 'admin']);
        $operarioRole = Role::create(['name' => 'operario']);

        // Crear usuario Admin
        $admin = User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@bodega.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($adminRole);

        // Crear usuario Operario
        $operario = User::create([
            'name' => 'Operario Bodega 1',
            'email' => 'operario@bodega.com',
            'password' => Hash::make('password123'),
        ]);
        $operario->assignRole($operarioRole);
    }
}
