@extends('layouts.admin')

@section('title', 'Achievement & Quest - EcoScan AI')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                ← Kembali ke Admin Dashboard
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                🏆 Achievement & Quest
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola achievement dan tantangan gamifikasi EcoScan AI.
            </p>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                ⚠️ {{ session('error') }}
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- ACHIEVEMENTS --}}
        {{-- ========================================================= --}}

        <div class="mb-10">

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">
                        🏆 Achievements
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $achievements->count() }} achievement tersedia.
                    </p>
                </div>
            </div>


            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                @foreach($achievements as $achievement)

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                        {{-- Icon + Name --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-3xl">
                                    {{ $achievement->icon }}
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900">
                                        {{ $achievement->name }}
                                    </h3>

                                    <p class="text-xs text-slate-400">
                                        {{ $achievement->code }}
                                    </p>
                                </div>

                            </div>

                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                +{{ $achievement->xp_reward }} XP
                            </span>

                        </div>


                        {{-- Description --}}
                        <p class="mt-4 min-h-[48px] text-sm leading-6 text-slate-600">
                            {{ $achievement->description }}
                        </p>


                        {{-- Target --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">
                                    Metric
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-700">
                                    {{ $achievement->metric }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">
                                    Target
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-700">
                                    {{ number_format($achievement->target) }}
                                </p>
                            </div>

                        </div>


                        {{-- Users --}}
                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">

                            <span class="text-sm text-slate-500">
                                👥 {{ number_format($achievement->users_count) }} user unlock
                            </span>

                        </div>


                        {{-- Admin Actions --}}
                        <details class="mt-4">

                            <summary
                                class="cursor-pointer list-none rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-200"
                            >
                                ⚡ God Mode
                            </summary>


                            <div class="mt-3 rounded-xl bg-slate-50 p-4">

                                <p class="mb-3 text-xs text-slate-500">
                                    Pilih user untuk unlock atau reset achievement ini.
                                </p>


                                <div class="space-y-3">

                                    @foreach($users as $user)

                                        @php
                                            $isUnlocked = $user->achievements
                                                ->contains('id', $achievement->id);
                                        @endphp


                                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white p-3 ring-1 ring-slate-100">

                                            <div class="min-w-0">

                                                <p class="truncate text-sm font-semibold text-slate-800">
                                                    {{ $user->name }}
                                                </p>

                                                <p class="truncate text-xs text-slate-400">
                                                    {{ $user->email }}
                                                </p>

                                            </div>


                                            @if($isUnlocked)

                                                <form
                                                    action="{{ route('admin.achievements.reset', [$user, $achievement]) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="whitespace-nowrap rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100"
                                                    >
                                                        🔄 Reset
                                                    </button>
                                                </form>

                                            @else

                                                <form
                                                    action="{{ route('admin.achievements.unlock', [$user, $achievement]) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="whitespace-nowrap rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700"
                                                    >
                                                        🔓 Unlock
                                                    </button>
                                                </form>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </details>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- QUESTS --}}
        {{-- ========================================================= --}}

        <div>

            <div class="mb-4">
                <h2 class="text-2xl font-bold text-slate-900">
                    ⚔️ Quest / Challenge
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Quest berasal dari konfigurasi gamifikasi dan tidak membutuhkan database tambahan.
                </p>
            </div>


            <div class="grid gap-5 md:grid-cols-2">

                @foreach($challenges as $challenge)

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                        <div class="flex items-start gap-4">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-3xl">
                                {{ $challenge['icon'] }}
                            </div>

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="font-bold text-slate-900">
                                        {{ $challenge['name'] }}
                                    </h3>

                                    <span class="rounded-full bg-violet-100 px-2.5 py-1 text-[10px] font-bold uppercase text-violet-700">
                                        {{ $challenge['period'] }}
                                    </span>

                                </div>

                                <div class="mt-3 grid grid-cols-3 gap-2">

                                    <div class="rounded-lg bg-slate-50 p-2">
                                        <p class="text-[10px] text-slate-400">
                                            Metric
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-slate-700">
                                            {{ $challenge['metric'] }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-slate-50 p-2">
                                        <p class="text-[10px] text-slate-400">
                                            Target
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-slate-700">
                                            {{ $challenge['target'] }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-amber-50 p-2">
                                        <p class="text-[10px] text-amber-600">
                                            Reward
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-amber-700">
                                            +{{ $challenge['xp_reward'] }} XP
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>
</div>
@endsection