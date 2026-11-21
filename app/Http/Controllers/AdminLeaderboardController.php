<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminLeaderboardController extends Controller
{
    public function index()
    {
        $users = User::with('ecoProfile')
            ->get()
            ->sortByDesc(fn ($user) => $user->ecoProfile?->xp ?? 0)
            ->values();

        return view('admin.leaderboard.index', compact('users'));
    }
}
