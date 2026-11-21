cat > resources/views/admin/leaderboard/index.blade.php <<'BLADE'
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-600">ADMIN PANEL</p>
                <h1 class="mt-1 text-3xl font-black text-slate-900">
                    Leaderboard
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Pantau ranking dan progress XP seluruh user EcoScan AI.
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
                ← Dashboard
            </a>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total User</p>
                <p class="mt-1 text-3xl font-black text-slate-900">
                    {{ $users->count() }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Top XP</p>
                <p class="mt-1 text-3xl font-black text-amber-600">
                    {{ $users->first()?->ecoProfile?->xp ?? 0 }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">User Aktif</p>
                <p class="mt-1 text-3xl font-black text-emerald-600">
                    {{ $users->whereNotNull('ecoProfile')->count() }}
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200">
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                Rank
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                User
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                Level
                            </th>
                            <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wider text-slate-500">
                                XP
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $index => $user)
                            @php
                                $rank = $index + 1;
                                $xp = $user->ecoProfile?->xp ?? 0;
                                $level = $user->ecoProfile?->level ?? 1;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-5">
                                    @if($rank === 1)
                                        <span class="text-2xl">🥇</span>
                                    @elseif($rank === 2)
                                        <span class="text-2xl">🥈</span>
                                    @elseif($rank === 3)
                                        <span class="text-2xl">🥉</span>
                                    @else
                                        <span class="font-black text-slate-400">
                                            #{{ $rank }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 font-black text-emerald-700">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-bold text-slate-800">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                {{ $user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex rounded-full bg-violet-50 px-3 py-1 text-sm font-bold text-violet-700">
                                        Level {{ $level }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <span class="text-lg font-black text-amber-600">
                                        {{ number_format($xp) }}
                                    </span>
                                    <span class="ml-1 text-sm font-semibold text-slate-400">
                                        XP
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <div class="text-4xl">🏆</div>
                                    <p class="mt-3 font-bold text-slate-700">
                                        Belum ada user
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
BLADE