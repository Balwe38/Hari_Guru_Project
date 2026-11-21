<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::withCount('users')
            ->orderBy('id')
            ->get();

        $users = User::orderBy('name')->get();

        $challenges = config('gamification.challenges', []);

        return view('admin.achievements.index', compact(
            'achievements',
            'users',
            'challenges'
        ));
    }

    public function unlock(Request $request, User $user, Achievement $achievement)
    {
        DB::transaction(function () use ($user, $achievement) {

            $alreadyUnlocked = DB::table('user_achievements')
                ->where('user_id', $user->id)
                ->where('achievement_id', $achievement->id)
                ->exists();

            if ($alreadyUnlocked) {
                return;
            }

            DB::table('user_achievements')->insert([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'unlocked_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $profile = $user->ecoProfile()->firstOrCreate(
                [],
                [
                    'xp' => 0,
                    'level' => 1,
                ]
            );

            $profile->xp += (int) $achievement->xp_reward;
            $profile->level = app(GamificationService::class)
                ->levelFor((int) $profile->xp);

            $profile->save();
        });

        return back()->with(
            'success',
            "Achievement {$achievement->name} berhasil di-unlock untuk {$user->name}."
        );
    }

    public function reset(User $user, Achievement $achievement)
    {
        $deleted = DB::table('user_achievements')
            ->where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->delete();

        if ($deleted) {
            return back()->with(
                'success',
                "Achievement {$achievement->name} berhasil di-reset untuk {$user->name}. XP tidak dikurangi."
            );
        }

        return back()->with(
            'error',
            "{$user->name} belum memiliki achievement {$achievement->name}."
        );
    }
}