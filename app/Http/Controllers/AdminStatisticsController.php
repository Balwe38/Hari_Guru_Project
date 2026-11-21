<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminStatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'scans' => Scan::count(),
            'xp' => (int) Scan::sum('xp_earned'),
            'confidence' => round((float) Scan::whereNotNull('confidence')->avg('confidence'), 1),
        ];

        $wasteStats = Scan::query()
            ->join('waste_types', 'scans.waste_type_id', '=', 'waste_types.id')
            ->select(
                'waste_types.name',
                DB::raw('COUNT(scans.id) as total')
            )
            ->groupBy('waste_types.id', 'waste_types.name')
            ->orderByDesc('total')
            ->get();

        $dailyScans = Scan::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('admin.statistics.index', compact(
            'stats',
            'wasteStats',
            'dailyScans'
        ));
    }
}
