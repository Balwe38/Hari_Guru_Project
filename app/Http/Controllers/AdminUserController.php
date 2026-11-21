<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
public function index(Request $request)
{
    $search = trim($request->input('search', ''));

    $users = User::query()
        ->with('ecoProfile')
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('admin.users.index', compact('users', 'search'));
}

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        // Jangan sampai admin mencabut role admin dari dirinya sendiri
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'Kamu tidak bisa menghapus role admin dari akun sendiri.');
        }

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->role}.");
    }

    public function updateProgress(Request $request, User $user)
{
    $request->validate([
        'xp' => ['required', 'integer', 'min:0', 'max:999999'],
        'level' => ['required', 'integer', 'min:1', 'max:999'],
    ]);

    $profile = $user->ecoProfile()->firstOrCreate(
        [],
        [
            'xp' => 0,
            'level' => 1,
        ]
    );

    $profile->update([
        'xp' => $request->xp,
        'level' => $request->level,
    ]);

    return back()->with(
        'success',
        "Progress {$user->name} berhasil diperbarui."
    );
}
}