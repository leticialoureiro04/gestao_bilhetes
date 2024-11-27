<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    // Função para listar os utilizadores
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $users = User::with('roles')->get();

        // Verificar e atribuir o papel 'cliente' para utilizadores que não têm nenhum papel
        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                $user->syncRoles(['cliente']); // Usa syncRoles para garantir que a role seja definida corretamente
            }
        }

        return view('users.index', compact('users'));
    }

    // Função para mostrar o formulário de criação de utilizador
    public function create()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        return view('users.create');  // Mostra o formulário de criação de utilizador
    }

    // Função para guardar o novo utilizador na base de dados
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $user->syncRoles(['cliente']);

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso e atribuído o papel de Cliente.');
    }

    // Função para mostrar o formulário de edição de utilizador
    public function edit(User $user)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        return view('users.edit', compact('user'));  // Envia o utilizador para a view de edição
    }

    // Função para atualizar o utilizador na base de dados
    public function update(Request $request, User $user)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Utilizador atualizado com sucesso.');
    }

    // Função para eliminar o utilizador da base de dados
    public function destroy(User $user)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $user->delete();  // Elimina o utilizador
        return redirect()->route('users.index')->with('success', 'Utilizador eliminado com sucesso.');
    }

    // Função para mostrar o formulário de atribuição de papel
    public function assignRoleForm(User $user)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);
        
        $roles = Role::all();
        return view('users.assign_role', compact('user', 'roles'));
    }

    // Função para atribuir um papel a um utilizador
    public function assignRole(Request $request, User $user)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $request->validate(['role' => 'required']);
        $user->syncRoles([$request->role]);
        return redirect()->route('users.index')->with('success', 'Papel atribuído com sucesso!');
    }

    // Função para exibir o perfil do utilizador autenticado
    public function profile()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $user = Auth::user(); // Obter o usuário autenticado
        return view('users.profile', compact('user'));
    }
}

