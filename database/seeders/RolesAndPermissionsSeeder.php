<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar um papel de admin
        $roleAdmin = Role::create(['name' => 'admin']);
        
        // Criar uma permissão
        $permission = Permission::create(['name' => 'manage users']);
        
        // Atribuir a permissão ao papel de admin
        $roleAdmin->givePermissionTo($permission);
        
        // Atribuir o papel de admin a um utilizador específico
        $user = User::find(1);  // Exemplo: Buscar o utilizador com ID 1
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
