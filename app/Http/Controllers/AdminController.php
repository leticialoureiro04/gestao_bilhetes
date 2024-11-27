<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Game;
use App\Models\Ticket;
use App\Models\SeatType;

class AdminController extends Controller
{
    // Exibir o Dashboard
    public function dashboard()
    {
        return view('dashboard'); // Certifique-se de ter o arquivo dashboard.blade.php
    }

    // Gerir Utilizadores
    public function manageUsers()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // Gerir Tipos de Assento
    public function manageSeatTypes()
    {
        $seatTypes = SeatType::all();
        return view('seat_types.index', compact('seatTypes'));
    }

    // Gerir Assentos
    public function manageSeats()
    {
        $seats = \App\Models\Seat::with(['stand', 'seatType'])->get();
        return view('seats.index', compact('seats'));
    }

    // Lista de Papéis (Roles)
    /*public function manageRoles()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }*/

    // Gerir Equipas
    public function manageTeams()
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    // Gerir Jogos
    public function manageGames()
    {
        $games = Game::all();
        return view('games.index', compact('games'));
    }

    // Gerir Tickets
    public function manageTickets()
    {
        $tickets = Ticket::with(['user', 'game'])->get();
        return view('tickets.index', compact('tickets'));
    }
}
