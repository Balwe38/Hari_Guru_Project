<?php

namespace App\Console\Commands;

use App\Models\Achievement;
use App\Models\EcoProfile;
use App\Models\Scan;
use App\Services\GamificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecalculateXp extends Command
{
    protected $signature = 'ecoscan:recalculate-xp';

    protected $description = 'Hitung ulang XP, level, dan achievement dari semua scan yang sudah ada';

    public function handle(GamificationService $gamification): int
    {
        DB::transaction(function () use ($gamification) {
            EcoProfile::current()->update(['xp' => 0, 'level' => 1]);
            Achievement::query()->update(['unlocked_at' => null]);
            Scan::query()->update(['xp_earned' => 0]);

            $seen = [];

            foreach (Scan::orderBy('id')->get() as $scan) {
                $hash      = $scan->image_hash ?: $this->hashOf($scan);
                $duplicate = $hash !== null && isset($seen[$hash]);

                if ($hash !== null) {
                    $seen[$hash] = true;

                    if ($scan->image_hash !== $hash) {
                        $scan->update(['image_hash' => $hash]);
                    }
                }

                $gamification->awardScan($scan, $duplicate);
            }
        });

        $profile = EcoProfile::current();
        $this->info("Selesai. Total XP: {$profile->xp}, level: {$profile->level}.");

        return self::SUCCESS;
    }

    private function hashOf(Scan $scan): ?string
    {
        $path = Storage::disk('public')->path($scan->image_path);

        return is_file($path) ? sha1_file($path) : null;
    }
}
