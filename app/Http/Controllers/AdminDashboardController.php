<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $stats = [
            'users' => User::count(),
            'scans' => Scan::count(),
            'achievements' => Achievement::count(),
            'admins' => User::where('role', 'admin')->count(),
            'xp' => (int) Scan::sum('xp_earned'),
            'today_scans' => Scan::whereDate('created_at', today())->count(),
        ];

        // Scan 7 hari terakhir
        $dailyScans = Scan::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Jenis sampah paling sering discan
        $topWasteTypes = Scan::query()
            ->join(
                'waste_types',
                'scans.waste_type_id',
                '=',
                'waste_types.id'
            )
            ->select(
                'waste_types.name',
                DB::raw('COUNT(scans.id) as total')
            )
            ->groupBy('waste_types.id', 'waste_types.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top user berdasarkan XP
        $topUsers = User::with('ecoProfile')
            ->get()
            ->sortByDesc(fn ($user) => $user->ecoProfile?->xp ?? 0)
            ->take(5)
            ->values();

        // Scan terbaru
        $recentScans = Scan::with(['user', 'wasteType'])
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'dailyScans',
            'topWasteTypes',
            'topUsers',
            'recentScans'
        ));
    }
}
