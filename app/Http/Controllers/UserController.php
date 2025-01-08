<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\GESFaturacaoAPIController;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Função para listar os utilizadores
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $users = User::with('roles')->get();



        return view('users.index', compact('users'));
    }

    // Função para mostrar o formulário de criação de utilizador
    public function create()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        return view('users.create');  // Mostra o formulário de criação de utilizador
    }

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]);

    // Criar o utilizador
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'country' => 'PT', // Adiciona o country diretamente
        'vatNumber' => '999999990', // Valor fixo
    ]);

    $user->syncRoles(['cliente']); // Atribuir papel 'cliente'

    // Chamar a API GESFaturacao
    try {
        $apiController = new GESFaturacaoAPIController();
        $response = $apiController->addClient($user->id);

        // Validar a resposta
        if ($response->getData()->success) {
            $user->id_gesfaturacao = $response->getData()->id_gesfaturacao;
            $user->save();
        } else {
            Log::error('Erro na integração com GESFaturacao', ['response' => $response->getData()]);
        }
    } catch (\Exception $e) {
        Log::error('Erro ao chamar a API GESFaturacao: ' . $e->getMessage());
    }

    // Fazer login automático do utilizador
    Auth::login($user);

    // Redirecionar para a dashboard
    return redirect()->route('dashboard')->with('success', 'Utilizador criado e logado com sucesso!');
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

    // Função para mostrar o perfil do utilizador autenticado
    public function profile()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $user = Auth::user();
        return view('users.profile', compact('user'));
    }
}
