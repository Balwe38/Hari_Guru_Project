
@extends('layouts.admin')

@section('title', 'Admin Dashboard - EcoScan AI')
@section('page_title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">

        {{-- HEADER --}}
        <div class="mb-8">
            <p class="text-sm font-semibold text-emerald-600">
                EcoScan AI
            </p>

            <h1 class="mt-1 text-3xl font-black text-slate-900">
                Admin Dashboard
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola user, XP, achievement, quest, scan, dan sistem EcoScan.
            </p>
        </div>

        {{-- STATISTICS --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">Total User</p>
                <p class="mt-2 text-3xl font-black text-slate-900">
                    {{ $stats['users'] }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">Total Scan</p>
                <p class="mt-2 text-3xl font-black text-slate-900">
                    {{ $stats['scans'] }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">Achievement</p>
                <p class="mt-2 text-3xl font-black text-slate-900">
                    {{ $stats['achievements'] }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">Admin</p>
                <p class="mt-2 text-3xl font-black text-emerald-600">
                    {{ $stats['admins'] }}
                </p>
            </div>

        </div>

        {{-- ADMIN CONTROLS --}}
        <div class="mt-8">
            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-900">
                    Admin Controls
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih menu untuk mengelola sistem EcoScan AI.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                {{-- USERS --}}
                <a href="{{ route('admin.users') }}"
                   class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-2xl">
                            👥
                        </div>

                        <span class="text-slate-300 transition group-hover:text-blue-500">
                            →
                        </span>
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        User Management
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Kelola user, role admin, XP, level, dan progress.
                    </p>
                </a>

                {{-- ACHIEVEMENTS --}}
                <a href="{{ route('admin.achievements') }}"
                   class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-2xl">
                            🏆
                        </div>

                        <span class="text-slate-300 transition group-hover:text-amber-500">
                            →
                        </span>
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        Achievements & Quests
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Unlock, reset, dan pantau achievement serta quest.
                    </p>
                </a>

                {{-- REWARDS --}}
                <a href="{{ route('admin.rewards') }}"
                   class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-pink-50 text-2xl">
                            🎁
                        </div>

                        <span class="text-slate-300 transition group-hover:text-pink-500">
                            →
                        </span>
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        Rewards
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Tambah, edit, aktifkan, nonaktifkan, dan hapus reward.
                    </p>
                </a>

                {{-- SCANS --}}
                <a href="{{ route('admin.scans') }}"
                   class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-2xl">
                            📸
                        </div>

                        <span class="text-slate-300 transition group-hover:text-emerald-500">
                            →
                        </span>
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        Scan Management
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Lihat aktivitas scan, hasil AI, confidence, XP, dan data scan.
                    </p>
                </a>

                {{-- LEADERBOARD --}}
                <a href="{{ route('admin.leaderboard') }}"
                   class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-2xl">
                            🥇
                        </div>

                        <span class="text-slate-300 transition group-hover:text-violet-500">
                            →
                        </span>
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        Leaderboard
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Lihat ranking user berdasarkan XP dan level.
                    </p>
                </a>

                {{-- STATISTICS --}}
                <a href="{{ route('admin.statistics') }}"
                   class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-50 text-2xl">
                            📊
                        </div>

                        <span class="text-slate-300 transition group-hover:text-cyan-500">
                            →
                        </span>
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        Statistics
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Pantau statistik scan, XP, confidence, dan jenis sampah.
                    </p>
                </a>

            </div>
        </div>

    </div>
</div>
@endsection
