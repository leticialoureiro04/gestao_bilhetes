<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Stadium;
use App\Models\Team;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // Exibe a lista de jogos para o usuário selecionar
    public function index()
    {
        $games = Game::with('stadium', 'teams')->get();
        return view('games.index', compact('games'));
    }

    // Redireciona para a escolha da planta do estádio com base no jogo selecionado
    public function select($game_id)
    {
        $game = Game::with('stadium')->findOrFail($game_id);
        return redirect()->route('stadium.plans', ['game_id' => $game->id]);
    }

    public function create()
    {
        $stadiums = Stadium::all();
        $teams = Team::all();
        return view('games.create', compact('stadiums', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'date_time' => 'required|date',
            'teams.home' => 'required|exists:teams,id|different:teams.away',
            'teams.away' => 'required|exists:teams,id|different:teams.home',
        ]);

        $game = Game::create($request->only(['stadium_id', 'date_time']));

        // Sincronizar as equipas com o jogo, atribuindo os papéis de "home" e "away"
        $game->teams()->attach($request->teams['home'], ['role' => 'home']);
        $game->teams()->attach($request->teams['away'], ['role' => 'away']);

        return redirect()->route('games.index')->with('success', 'Jogo criado com sucesso!');
    }

    public function edit(Game $game)
    {
        $stadiums = Stadium::all();
        $teams = Team::all();
        return view('games.edit', compact('game', 'stadiums', 'teams'));
    }

    public function update(Request $request, Game $game)
    {
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'date_time' => 'required|date',
            'teams.home' => 'required|exists:teams,id|different:teams.away',
            'teams.away' => 'required|exists:teams,id|different:teams.home',
        ]);

        $game->update($request->only(['stadium_id', 'date_time']));

        // Sincronizar as equipas com o jogo
        $game->teams()->sync([
            $request->teams['home'] => ['role' => 'home'],
            $request->teams['away'] => ['role' => 'away'],
        ]);

        return redirect()->route('games.index')->with('success', 'Jogo atualizado com sucesso!');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')->with('success', 'Jogo eliminado com sucesso!');
    }
}



