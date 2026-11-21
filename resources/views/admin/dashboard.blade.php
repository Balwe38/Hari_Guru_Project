
@extends('layouts.admin')

@section('title', 'Admin Dashboard - EcoScan AI')

@section('page_title', 'Admin Dashboard')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div>
        <p class="text-sm font-bold text-green-700">
            EcoScan AI
        </p>

        <h1 class="mt-1 text-3xl font-black tracking-tight text-gray-900">
            Admin Dashboard
        </h1>

        <p class="mt-2 text-sm text-gray-500">
            Pantau aktivitas dan kelola sistem EcoScan AI.
        </p>
    </div>


    {{-- STATISTICS --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-6">

        {{-- Users --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Total User
                </p>
                <span class="text-xl">👥</span>
            </div>

            <p class="mt-3 text-3xl font-black text-gray-900">
                {{ $stats['users'] }}
            </p>
        </div>


        {{-- Scans --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Total Scan
                </p>
                <span class="text-xl">📸</span>
            </div>

            <p class="mt-3 text-3xl font-black text-gray-900">
                {{ $stats['scans'] }}
            </p>
        </div>


        {{-- Today's scans --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Scan Hari Ini
                </p>
                <span class="text-xl">📅</span>
            </div>

            <p class="mt-3 text-3xl font-black text-green-700">
                {{ $stats['today_scans'] }}
            </p>
        </div>


        {{-- XP --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Total XP
                </p>
                <span class="text-xl">⚡</span>
            </div>

            <p class="mt-3 text-3xl font-black text-gray-900">
                {{ number_format($stats['xp']) }}
            </p>
        </div>


        {{-- Achievements --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Achievement
                </p>
                <span class="text-xl">🏆</span>
            </div>

            <p class="mt-3 text-3xl font-black text-gray-900">
                {{ $stats['achievements'] }}
            </p>
        </div>


        {{-- Admin --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Admin
                </p>
                <span class="text-xl">🛡️</span>
            </div>

            <p class="mt-3 text-3xl font-black text-green-700">
                {{ $stats['admins'] }}
            </p>
        </div>

    </div>


    {{-- CHART + TOP WASTE --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- DAILY SCANS --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">

            <div class="mb-6">
                <h2 class="text-lg font-black text-gray-900">
                    Aktivitas Scan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Jumlah scan dalam 7 hari terakhir.
                </p>
            </div>

            @php
                $maxDailyScan = max(
                    1,
                    $dailyScans->max('total') ?? 1
                );
            @endphp

            <div class="space-y-4">

                @for ($i = 6; $i >= 0; $i--)

                    @php
                        $date = now()->subDays($i);
                        $item = $dailyScans->first(
                            fn ($scan) => \Carbon\Carbon::parse($scan->date)->isSameDay($date)
                        );

                        $total = $item?->total ?? 0;
                        $width = $total > 0
                            ? max(6, ($total / $maxDailyScan) * 100)
                            : 0;
                    @endphp

                    <div class="flex items-center gap-3">

                        <div class="w-12 shrink-0 text-xs font-semibold text-gray-500">
                            {{ $date->format('D') }}
                        </div>

                        <div class="h-8 flex-1 overflow-hidden rounded-lg bg-gray-100">

                            @if ($total > 0)
                                <div
                                    class="flex h-full items-center rounded-lg bg-green-700 px-3 text-xs font-bold text-white"
                                    style="width: {{ $width }}%"
                                >
                                    {{ $total }}
                                </div>
                            @endif

                        </div>

                        <div class="w-8 text-right text-xs font-bold text-gray-600">
                            {{ $total }}
                        </div>

                    </div>

                @endfor

            </div>

        </div>


        {{-- TOP WASTE --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

            <div class="mb-6">
                <h2 class="text-lg font-black text-gray-900">
                    Sampah Terpopuler
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Jenis sampah yang paling sering discan.
                </p>
            </div>

            <div class="space-y-4">

                @forelse ($topWasteTypes as $index => $waste)

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-100 text-sm font-black text-green-800">
                            {{ $index + 1 }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-bold text-gray-800">
                                {{ $waste->name }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ $waste->total }} scan
                            </p>

                        </div>

                    </div>

                @empty

                    <p class="text-sm text-gray-400">
                        Belum ada data scan.
                    </p>

                @endforelse

            </div>

        </div>

    </div>


    {{-- TOP USERS + RECENT SCANS --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- TOP USERS --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

            <div class="mb-6 flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-black text-gray-900">
                        Top Users
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        User dengan XP tertinggi.
                    </p>
                </div>

                <a href="{{ route('admin.leaderboard') }}"
                   class="text-sm font-bold text-green-700 hover:text-green-800">
                    Lihat semua →
                </a>

            </div>


            <div class="space-y-3">

                @forelse ($topUsers as $index => $user)

                    <div class="flex items-center gap-3 rounded-xl border border-gray-100 p-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-black text-green-800">
                            {{ $index + 1 }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-bold text-gray-800">
                                {{ $user->name }}
                            </p>

                            <p class="truncate text-xs text-gray-400">
                                {{ $user->email }}
                            </p>

                        </div>

                        <div class="text-right">

                            <p class="text-sm font-black text-green-700">
                                {{ number_format($user->ecoProfile?->xp ?? 0) }}
                            </p>

                            <p class="text-[11px] text-gray-400">
                                XP
                            </p>

                        </div>

                    </div>

                @empty

                    <p class="text-sm text-gray-400">
                        Belum ada user.
                    </p>

                @endforelse

            </div>

        </div>


        {{-- RECENT SCANS --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

            <div class="mb-6 flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-black text-gray-900">
                        Scan Terbaru
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Aktivitas scan terakhir.
                    </p>
                </div>

                <a href="{{ route('admin.scans') }}"
                   class="text-sm font-bold text-green-700 hover:text-green-800">
                    Lihat semua →
                </a>

            </div>


            <div class="space-y-3">

                @forelse ($recentScans as $scan)

                    <div class="flex items-center gap-3 rounded-xl border border-gray-100 p-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-100 text-lg">
                            ♻️
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-bold text-gray-800">
                                {{ $scan->wasteType?->name ?? 'Tidak diketahui' }}
                            </p>

                            <p class="truncate text-xs text-gray-400">
                                {{ $scan->user?->name ?? 'User tidak diketahui' }}
                            </p>

                        </div>

                        <div class="text-right">

                            @if ($scan->confidence !== null)
                                <p class="text-xs font-bold text-green-700">
                                    {{ number_format((float) $scan->confidence, 1) }}%
                                </p>
                            @endif

                            <p class="text-[11px] text-gray-400">
                                {{ $scan->created_at->diffForHumans() }}
                            </p>

                        </div>

                    </div>

                @empty

                    <p class="text-sm text-gray-400">
                        Belum ada scan.
                    </p>

                @endforelse

            </div>

        </div>

    </div>


    {{-- QUICK ACTIONS --}}
    <div>

        <div class="mb-4">

            <h2 class="text-lg font-black text-gray-900">
                Quick Actions
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Akses cepat ke fitur administrasi.
            </p>

        </div>


        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <a href="{{ route('admin.users') }}"
               class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-xl">
                    👥
                </div>

                <p class="font-black text-gray-900">
                    Kelola Users
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Atur role, XP, dan level user.
                </p>

            </a>


            <a href="{{ route('admin.achievements') }}"
               class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-xl">
                    🏆
                </div>

                <p class="font-black text-gray-900">
                    Achievements
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Kelola pencapaian user.
                </p>

            </a>


            <a href="{{ route('admin.rewards') }}"
               class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-xl">
                    🎁
                </div>

                <p class="font-black text-gray-900">
                    Rewards
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Kelola hadiah EcoScan.
                </p>

            </a>


            <a href="{{ route('admin.statistics') }}"
               class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-xl">
                    📊
                </div>

                <p class="font-black text-gray-900">
                    Statistics
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Lihat statistik lengkap.
                </p>

            </a>

        </div>

    </div>

</div>

@endsection
