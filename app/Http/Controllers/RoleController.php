<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class RoleController extends Controller
{
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles']);
        Role::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Papel criado com sucesso!');
    }

    public function edit(Role $role)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        $role->update(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Papel atualizado com sucesso!');
    }

    public function destroy(Role $role)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Papel removido com sucesso!');
    }
}

