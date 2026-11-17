<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\EcoProfile;
use App\Services\GamificationService;

class AchievementController extends Controller
{
    public function index(GamificationService $gamification)
    {
        $metrics = $gamification->metrics();

        $achievements = Achievement::orderBy('id')->get()->map(function ($a) use ($metrics) {
            $current = min($metrics[$a->metric] ?? 0, $a->target);

            return [
                'achievement' => $a,
                'current'     => $current,
                'pct'         => $a->target > 0 ? (int) round($current / $a->target * 100) : 0,
            ];
        });

        $eco = $gamification->progress(EcoProfile::current());

        return view('pencapaian', [
            'achievements' => $achievements,
            'eco'          => $eco,
            'unlocked'     => $achievements->filter(fn ($row) => $row['achievement']->isUnlocked())->count(),
        ]);
    }
}
