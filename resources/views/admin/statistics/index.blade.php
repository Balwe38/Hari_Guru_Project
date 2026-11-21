
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">ADMIN PANEL</p>
                <h1 class="mt-1 text-3xl font-black text-slate-900">
                    Statistics
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Ringkasan aktivitas dan performa EcoScan AI.
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
                ← Dashboard
            </a>
        </div>

        {{-- SUMMARY --}}
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total User</p>
                <p class="mt-2 text-3xl font-black text-slate-900">
                    {{ number_format($stats['users']) }}
                </p>
                <p class="mt-1 text-xs text-slate-400">akun terdaftar</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total Scan</p>
                <p class="mt-2 text-3xl font-black text-emerald-600">
                    {{ number_format($stats['scans']) }}
                </p>
                <p class="mt-1 text-xs text-slate-400">scan berhasil tersimpan</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total XP</p>
                <p class="mt-2 text-3xl font-black text-amber-600">
                    {{ number_format($stats['xp']) }}
                </p>
                <p class="mt-1 text-xs text-slate-400">XP dari aktivitas scan</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Avg. Confidence</p>
                <p class="mt-2 text-3xl font-black text-violet-600">
                    {{ number_format($stats['confidence'], 1) }}%
                </p>
                <p class="mt-1 text-xs text-slate-400">rata-rata hasil AI</p>
            </div>

        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- WASTE TYPES --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-5">
                    <h2 class="text-xl font-black text-slate-900">
                        Jenis Sampah
                    </h2>
                    <p class="text-sm text-slate-500">
                        Sampah yang paling sering dikenali AI.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse($wasteStats as $item)
                        <div>
                            <div class="mb-1 flex items-center justify-between">
                                <span class="font-bold text-slate-700">
                                    {{ $item->name }}
                                </span>

                                <span class="text-sm font-black text-emerald-600">
                                    {{ number_format($item->total) }}
                                </span>
                            </div>

                            @php
                                $maxWaste = $wasteStats->first()?->total ?? 1;
                                $width = ($item->total / max($maxWaste, 1)) * 100;
                            @endphp

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-emerald-500"
                                    style="width: {{ $width }}%"
                                ></div>
                            </div>
                        </div>
                    @empty
                        <p class="py-8 text-center text-sm text-slate-400">
                            Belum ada data scan.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- DAILY SCANS --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-5">
                    <h2 class="text-xl font-black text-slate-900">
                        Aktivitas 7 Hari
                    </h2>
                    <p class="text-sm text-slate-500">
                        Jumlah scan dalam 7 hari terakhir.
                    </p>
                </div>

                <div class="space-y-3">
                    @forelse($dailyScans as $day)
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}
                                </p>
                            </div>

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-black text-emerald-700">
                                {{ number_format($day->total) }} scan
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <div class="text-4xl">📊</div>
                            <p class="mt-3 text-sm text-slate-400">
                                Belum ada aktivitas dalam 7 hari terakhir.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
