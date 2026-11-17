<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\EcoProfile;
use App\Models\Scan;
use App\Services\GamificationService;

class DashboardController extends Controller
{
    public function index(GamificationService $gamification)
    {
        $hitung = fn ($kategori) => Scan::whereHas(
            'wasteType.category',
            fn ($q) => $q->where('name', $kategori)
        )->count();

        $stats = [
            'total'     => Scan::count(),
            'organik'   => $hitung('Organik'),
            'anorganik' => $hitung('Anorganik'),
            'lainnya'   => $hitung('Lainnya'),
        ];

        $recent = Scan::with('wasteType.category')->latest()->take(5)->get()
            ->map(fn ($s) => [
                'nama'     => $s->wasteType->name ?? 'Tidak dikenali',
                'kategori' => $s->wasteType->category->name ?? '-',
            ]);

        $eco = $gamification->progress(EcoProfile::current());

        $achievementsUnlocked = Achievement::whereNotNull('unlocked_at')->count();
        $achievementsTotal    = Achievement::count();

        return view('dashboard', compact('stats', 'recent', 'eco', 'achievementsUnlocked', 'achievementsTotal'));
    }
}
