<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Scan;
use App\Models\Achievement;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'scans' => Scan::count(),
            'achievements' => Achievement::count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}