<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\EcoProfile;
use App\Models\Scan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /** Level berdasarkan total XP. */
    public function levelFor(int $xp): int
    {
        $level = 1;

        foreach (config('gamification.levels') as $number => $def) {
            if ($xp >= $def['xp']) {
                $level = $number;
            }
        }

        return $level;
    }

    /** Data siap tampil untuk progress bar level. */
    public function progress(EcoProfile $profile): array
    {
        $levels  = config('gamification.levels');
        $level   = $this->levelFor($profile->xp);
        $current = $levels[$level];
        $next    = $levels[$level + 1] ?? null;

        if ($next) {
            $span = $next['xp'] - $current['xp'];
            $into = $profile->xp - $current['xp'];
            $pct  = (int) round($into / $span * 100);
        } else {
            $span = 0;
            $into = 0;
            $pct  = 100;
        }

        return [
            'level'      => $level,
            'name'       => $current['name'],
            'icon'       => $current['icon'],
            'xp'         => $profile->xp,
            'into'       => $into,
            'span'       => $span,
            'pct'        => $pct,
            'is_max'     => $next === null,
            'next_name'  => $next['name'] ?? null,
            'next_icon'  => $next['icon'] ?? null,
            'xp_to_next' => $next ? $next['xp'] - $profile->xp : 0,
        ];
    }

    /**
     * Beri XP untuk satu scan baru dan buka achievement yang tercapai.
     * Hanya scan yang dikenali AI yang mendapat XP, dan foto yang sama
     * (isi file identik) tidak dihitung dua kali.
     */
    public function awardScan(Scan $scan, bool $duplicate = false): array
    {
        $result = [
            'duplicate'       => $duplicate,
            'xp_gained'       => 0,
            'breakdown'       => [],
            'achievements'    => [],
            'achievement_xp'  => 0,
            'leveled_up'      => false,
            'progress'        => $this->progress(EcoProfile::current()),
        ];

        if (! $scan->waste_type_id || $duplicate) {
            return $result;
        }

        return DB::transaction(function () use ($scan, $result) {
            $cfg     = config('gamification.xp');
            $profile = EcoProfile::lockForUpdate()->findOrFail(EcoProfile::current()->id);
            $oldLevel = $this->levelFor($profile->xp);

            $breakdown = [['label' => 'Scan berhasil dikenali', 'xp' => $cfg['scan']]];

            if ((float) $scan->confidence >= $cfg['high_confidence_threshold']) {
                $breakdown[] = ['label' => 'Keyakinan AI tinggi', 'xp' => $cfg['high_confidence']];
            }

            $seenBefore = Scan::where('waste_type_id', $scan->waste_type_id)
                ->where('xp_earned', '>', 0)
                ->where('id', '<', $scan->id)
                ->exists();

            if (! $seenBefore) {
                $breakdown[] = ['label' => 'Jenis sampah baru', 'xp' => $cfg['new_type']];
            }

            $scanXp = array_sum(array_column($breakdown, 'xp'));
            $scan->update(['xp_earned' => $scanXp]);

            $unlocked      = $this->unlockAchievements($scan->created_at ?? now());
            $achievementXp = array_sum(array_column($unlocked, 'xp_reward'));

            $profile->xp   += $scanXp + $achievementXp;
            $profile->level = $this->levelFor($profile->xp);
            $profile->save();

            $result['xp_gained']      = $scanXp;
            $result['breakdown']      = $breakdown;
            $result['achievements']   = $unlocked;
            $result['achievement_xp'] = $achievementXp;
            $result['leveled_up']     = $profile->level > $oldLevel;
            $result['progress']       = $this->progress($profile);

            return $result;
        });
    }

    /** Angka-angka yang dipakai untuk menilai achievement. */
    public function metrics(): array
    {
        $earning = fn () => Scan::where('xp_earned', '>', 0);

        $dates = $earning()->pluck('created_at')
            ->map(fn ($date) => $date->toDateString())
            ->unique()
            ->sort()
            ->values();

        return [
            'scans'           => $earning()->count(),
            'distinct_types'  => $earning()->distinct()->count('waste_type_id'),
            'anorganik_scans' => $earning()
                ->whereHas('wasteType.category', fn ($q) => $q->where('name', 'Anorganik'))
                ->count(),
            'streak_days'     => $this->longestStreak($dates),
        ];
    }

    /** Buka achievement yang targetnya sudah tercapai; kembalikan yang baru terbuka. */
    private function unlockAchievements($when): array
    {
        $metrics  = $this->metrics();
        $unlocked = [];

        foreach (Achievement::whereNull('unlocked_at')->orderBy('id')->get() as $achievement) {
            if (($metrics[$achievement->metric] ?? 0) >= $achievement->target) {
                $achievement->update(['unlocked_at' => $when]);

                $unlocked[] = [
                    'name'        => $achievement->name,
                    'icon'        => $achievement->icon,
                    'description' => $achievement->description,
                    'xp_reward'   => $achievement->xp_reward,
                ];
            }
        }

        return $unlocked;
    }

    private function longestStreak(Collection $dates): int
    {
        $best = 0;
        $run  = 0;
        $prev = null;

        foreach ($dates as $date) {
            $day = Carbon::parse($date);
            $run = ($prev && $prev->copy()->addDay()->isSameDay($day)) ? $run + 1 : 1;
            $best = max($best, $run);
            $prev = $day;
        }

        return $best;
    }
}
