<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class AdminRewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::withCount('redemptions')
            ->latest()
            ->get();

        return view('admin.rewards.index', compact('rewards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:rewards,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['required', 'string', 'max:16'],
            'cost_xp' => ['required', 'integer', 'min:0', 'max:999999'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Reward::create($validated);

        return back()->with(
            'success',
            "Reward {$validated['name']} berhasil ditambahkan."
        );
    }

    public function update(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:rewards,name,' . $reward->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['required', 'string', 'max:16'],
            'cost_xp' => ['required', 'integer', 'min:0', 'max:999999'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $reward->update($validated);

        return back()->with(
            'success',
            "Reward {$reward->name} berhasil diperbarui."
        );
    }

    public function toggle(Reward $reward)
    {
        $reward->update([
            'is_active' => ! $reward->is_active,
        ]);

        $status = $reward->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with(
            'success',
            "Reward {$reward->name} berhasil {$status}."
        );
    }

    public function destroy(Reward $reward)
    {
        if ($reward->redemptions()->exists()) {
            return back()->with(
                'error',
                "Reward {$reward->name} tidak bisa dihapus karena sudah pernah ditukar user. Nonaktifkan saja."
            );
        }

        $name = $reward->name;

        $reward->delete();

        return back()->with(
            'success',
            "Reward {$name} berhasil dihapus."
        );
    }
}