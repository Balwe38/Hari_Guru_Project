@extends('layouts.app')

@section('title', 'Kelola User - EcoScan AI')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">

        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                ← Kembali ke Admin Dashboard
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Kelola User
            </h1>

            <p class="mt-2 text-slate-500">
                Atur role dan akses pengguna EcoScan AI.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                User
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Email
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Role
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Terdaftar
                            </th>

                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($users as $user)

                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $user->name }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        ID #{{ $user->id }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <td class="px-6 py-4">

                                    @if($user->role === 'admin')

                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            ADMIN
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            USER
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $user->created_at?->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-right">

                                    @if($user->id === auth()->id())

                                        <span class="text-xs text-slate-400">
                                            Akun kamu
                                        </span>

                                    @else

                                        <form
                                            action="{{ route('admin.users.role', $user) }}"
                                            method="POST"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            @if($user->role === 'admin')

                                                <input type="hidden" name="role" value="user">

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200"
                                                >
                                                    Jadikan User
                                                </button>

                                            @else

                                                <input type="hidden" name="role" value="admin">

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                                                >
                                                    Jadikan Admin
                                                </button>

                                            @endif

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada user.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-6 py-4">
                {{ $users->links() }}
            </div>

        </div>

    </div>
</div>
@endsectionphp artisan optimize:clear