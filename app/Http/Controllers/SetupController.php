<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SetupController extends Controller
{
    public function setupRolesAndPermissions()
    {
        // Criar o papel 'admin' se não existir
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);

        // Criar o papel 'cliente' se não existir
        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);

        // Criar permissões específicas se não existirem
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage users']);
        $viewTicketsPermission = Permission::firstOrCreate(['name' => 'view tickets']);
        $buyTicketsPermission = Permission::firstOrCreate(['name' => 'buy tickets']);

        // Atribuir a permissão de 'manage users' ao papel 'admin'
        if (!$roleAdmin->hasPermissionTo($manageUsersPermission)) {
            $roleAdmin->givePermissionTo($manageUsersPermission);
        }

        // Atribuir permissões de 'view tickets' e 'buy tickets' ao papel 'cliente'
        if (!$roleCliente->hasPermissionTo($viewTicketsPermission)) {
            $roleCliente->givePermissionTo($viewTicketsPermission);
        }

        if (!$roleCliente->hasPermissionTo($buyTicketsPermission)) {
            $roleCliente->givePermissionTo($buyTicketsPermission);
        }

        // Atribuir o papel 'admin' a um utilizador específico
        $user = User::find(1);  
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        return "Papéis e permissões configurados com sucesso!";
    }
}


