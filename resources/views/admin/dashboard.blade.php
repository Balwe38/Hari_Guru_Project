@extends('layouts.app')

@section('title', 'Admin Dashboard - EcoScan AI')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">

        <div class="mb-8">
            <p class="text-sm font-medium text-emerald-600">
                EcoScan AI
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                Admin Dashboard
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola user, XP, achievement, quest, scan, dan sistem EcoScan.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total User</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">
    {{ $stats['users'] }}
</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total Scan</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">
    {{ $stats['scans'] }}
</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Achievement</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">
    {{ $stats['achievements'] }}
</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Admin</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">
    {{ $stats['admins'] }}
</p>
            </div>

        </div>

        <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">
                Admin Controls
            </h2>

            <p class="mt-2 text-slate-500">
                Panel kontrol EcoScan AI akan kita bangun di sini.
            </p>
        </div>

    </div>
</div>
@endsection