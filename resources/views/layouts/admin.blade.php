
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin Panel - EcoScan AI')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

<div class="min-h-screen lg:flex">

    {{-- SIDEBAR --}}
    <aside class="w-full shrink-0 bg-slate-950 text-white lg:min-h-screen lg:w-64">

        <div class="flex h-full flex-col">

            {{-- BRAND --}}
            <div class="border-b border-white/10 px-6 py-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500 text-xl shadow-lg shadow-emerald-500/20">
                        ♻
                    </div>

                    <div>
                        <p class="text-lg font-black tracking-tight">
                            EcoScan AI
                        </p>

                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-400">
                            Admin Panel
                        </p>
                    </div>
                </a>
            </div>

            {{-- NAVIGATION --}}
            <nav class="flex-1 px-3 py-5">

                <p class="mb-3 px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">
                    Management
                </p>

                <div class="space-y-1">

                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.dashboard')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">🏠</span>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.users') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.users*')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">👥</span>
                        Users
                    </a>

                    <a href="{{ route('admin.achievements') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.achievements*')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">🏆</span>
                        Achievements
                    </a>

                    <a href="{{ route('admin.rewards') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.rewards*')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">🎁</span>
                        Rewards
                    </a>

                    <a href="{{ route('admin.scans') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.scans*')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">📸</span>
                        Scans
                    </a>

                    <a href="{{ route('admin.leaderboard') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.leaderboard*')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">🥇</span>
                        Leaderboard
                    </a>

                    <a href="{{ route('admin.statistics') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold transition
                       {{ request()->routeIs('admin.statistics*')
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg">📊</span>
                        Statistics
                    </a>

                </div>

            </nav>

            {{-- BOTTOM --}}
            <div class="border-t border-white/10 p-4">

                <div class="mb-3 rounded-xl bg-white/5 px-3 py-3">
                    <p class="text-xs text-slate-500">
                        Signed in as
                    </p>

                    <p class="mt-1 truncate text-sm font-bold text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="truncate text-xs text-slate-500">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/5 hover:text-white">
                    <span>↩</span>
                    Kembali ke App
                </a>

            </div>

        </div>
    </aside>

    {{-- MAIN --}}
    <div class="min-w-0 flex-1">

        {{-- TOP BAR --}}
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center justify-between px-5 sm:px-8">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        EcoScan AI
                    </p>

                    <p class="text-sm font-black text-slate-800">
                        @yield('page_title', 'Admin Panel')
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-bold text-slate-800">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-emerald-600">
                            Administrator
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 font-black text-emerald-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

            </div>
        </header>

        {{-- CONTENT --}}
        <main class="p-5 sm:p-8">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>
