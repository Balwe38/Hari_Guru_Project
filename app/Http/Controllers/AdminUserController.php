<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
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
}