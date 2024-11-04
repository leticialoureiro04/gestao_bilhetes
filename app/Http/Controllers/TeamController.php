<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Função para listar todas as equipas
    public function index()
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    // Função para mostrar o formulário de criação de equipa
    public function create()
    {
        return view('teams.create');
    }

    // Função para guardar a nova equipa na base de dados
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'city' => 'nullable|max:255',
            'founded' => 'nullable|date',
        ]);

        Team::create($request->all());

        return redirect()->route('teams.index')->with('success', 'Equipa criada com sucesso.');
    }

    // Função para mostrar o formulário de edição de equipa
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    // Função para atualizar a equipa na base de dados
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|max:255',
            'city' => 'nullable|max:255',
            'founded' => 'nullable|date',
        ]);

        $team->update($request->all());

        return redirect()->route('teams.index')->with('success', 'Equipa atualizada com sucesso.');
    }

    // Função para eliminar uma equipa
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Equipa eliminada com sucesso.');
    }
}
