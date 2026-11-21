@extends('layouts.admin')

@section('title', 'Users Management - EcoScan AI')
@section('page_title', 'Users Management')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900">
                Users Management
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola user, role, XP, dan level EcoScan AI.
            </p>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <form method="GET"
              action="{{ route('admin.users') }}"
              class="flex flex-col gap-3 sm:flex-row">

            <div class="relative flex-1">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    🔎
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama atau email user..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-800 outline-none transition focus:border-green-600 focus:bg-white focus:ring-2 focus:ring-green-100"
                >
            </div>

            <button
                type="submit"
                class="rounded-xl bg-green-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-green-900"
            >
                Cari
            </button>

            @if (($search ?? '') !== '')
                <a
                    href="{{ route('admin.users') }}"
                    class="rounded-xl border border-gray-200 px-6 py-3 text-center text-sm font-bold text-gray-600 transition hover:bg-gray-50"
                >
                    Reset
                </a>
            @endif

        </form>

        @if (($search ?? '') !== '')
            <p class="mt-3 text-xs text-gray-500">
                Menampilkan hasil untuk:
                <span class="font-bold text-gray-700">
                    "{{ $search }}"
                </span>
            </p>
        @endif
    </div>

    {{-- Users Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        {{-- Table Header --}}
        <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-black text-gray-900">
                    Daftar Users
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    {{ $users->total() }} user ditemukan
                </p>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full text-left">

                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        <th class="px-5 py-4 text-xs font-black uppercase tracking-wider text-gray-500">
                            User
                        </th>

                        <th class="px-5 py-4 text-xs font-black uppercase tracking-wider text-gray-500">
                            Role
                        </th>

                        <th class="px-5 py-4 text-xs font-black uppercase tracking-wider text-gray-500">
                            XP
                        </th>

                        <th class="px-5 py-4 text-xs font-black uppercase tracking-wider text-gray-500">
                            Level
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wider text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($users as $user)

                        <tr class="transition hover:bg-gray-50">

                            {{-- User --}}
                            <td class="px-5 py-5">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-100 font-black text-green-800">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="truncate font-bold text-gray-900">
                                            {{ $user->name }}
                                        </p>

                                        <p class="truncate text-sm text-gray-500">
                                            {{ $user->email }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-5">

                                <form
                                    method="POST"
                                    action="{{ route('admin.users.role', $user) }}"
                                    class="flex items-center gap-2"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <select
                                        name="role"
                                        onchange="this.form.submit()"
                                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-bold outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                    >
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>
                                            User
                                        </option>

                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                    </select>
                                </form>

                            </td>

                            {{-- XP --}}
                            <td class="px-5 py-5">
                                <span class="font-black text-green-700">
                                    {{ number_format($user->ecoProfile?->xp ?? 0) }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    XP
                                </span>
                            </td>

                            {{-- Level --}}
                            <td class="px-5 py-5">
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-black text-green-800">
                                    Level {{ $user->ecoProfile?->level ?? 1 }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-5">
                                <details class="relative">

                                    <summary class="ml-auto flex w-fit cursor-pointer list-none items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold text-gray-600 transition hover:bg-gray-50">
                                        ⚙️ Manage
                                    </summary>

                                    <div class="absolute right-0 z-10 mt-2 w-72 rounded-2xl border border-gray-100 bg-white p-4 shadow-xl">

                                        <p class="mb-3 text-xs font-black uppercase tracking-wider text-gray-400">
                                            Edit Progress
                                        </p>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.progress', $user) }}"
                                            class="space-y-3"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <div>
                                                <label class="mb-1 block text-xs font-bold text-gray-600">
                                                    XP
                                                </label>

                                                <input
                                                    type="number"
                                                    name="xp"
                                                    min="0"
                                                    max="999999"
                                                    value="{{ $user->ecoProfile?->xp ?? 0 }}"
                                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-green-600 focus:bg-white focus:ring-2 focus:ring-green-100"
                                                    required
                                                >
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-xs font-bold text-gray-600">
                                                    Level
                                                </label>

                                                <input
                                                    type="number"
                                                    name="level"
                                                    min="1"
                                                    max="999"
                                                    value="{{ $user->ecoProfile?->level ?? 1 }}"
                                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-green-600 focus:bg-white focus:ring-2 focus:ring-green-100"
                                                    required
                                                >
                                            </div>

                                            <button
                                                type="submit"
                                                class="w-full rounded-xl bg-green-800 px-4 py-2.5 text-xs font-black text-white transition hover:bg-green-900"
                                            >
                                                Simpan Progress
                                            </button>

                                        </form>

                                    </div>

                                </details>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">

                                <div class="text-4xl">
                                    🔎
                                </div>

                                <p class="mt-3 font-bold text-gray-700">
                                    User tidak ditemukan
                                </p>

                                <p class="mt-1 text-sm text-gray-400">
                                    Coba gunakan nama atau email yang berbeda.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-gray-100 md:hidden">

            @forelse ($users as $user)

                <div class="p-5">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex min-w-0 items-center gap-3">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-100 font-black text-green-800">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate font-bold text-gray-900">
                                    {{ $user->name }}
                                </p>

                                <p class="truncate text-sm text-gray-500">
                                    {{ $user->email }}
                                </p>
                            </div>

                        </div>

                        @if ($user->role === 'admin')
                            <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-black text-green-800">
                                Admin
                            </span>
                        @else
                            <span class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-black text-gray-600">
                                User
                            </span>
                        @endif

                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">

                        <div class="rounded-xl bg-gray-50 p-3">
                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">
                                XP
                            </p>

                            <p class="mt-1 font-black text-green-700">
                                {{ number_format($user->ecoProfile?->xp ?? 0) }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-3">
                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Level
                            </p>

                            <p class="mt-1 font-black text-gray-800">
                                {{ $user->ecoProfile?->level ?? 1 }}
                            </p>
                        </div>

                    </div>

                    <div class="mt-4">

                        <details>

                            <summary class="cursor-pointer rounded-xl border border-gray-200 px-4 py-3 text-center text-xs font-black text-gray-600 transition hover:bg-gray-50">
                                ⚙️ Manage User
                            </summary>

                            <div class="mt-3 rounded-xl bg-gray-50 p-4">

                                {{-- Role --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.users.role', $user) }}"
                                    class="mb-4"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <label class="mb-1 block text-xs font-bold text-gray-600">
                                        Role
                                    </label>

                                    <div class="flex gap-2">

                                        <select
                                            name="role"
                                            class="min-w-0 flex-1 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-bold outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                        >
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>
                                                User
                                            </option>

                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        </select>

                                        <button
                                            type="submit"
                                            class="rounded-xl bg-green-800 px-4 py-2 text-xs font-black text-white"
                                        >
                                            Simpan
                                        </button>

                                    </div>
                                </form>

                                {{-- Progress --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.users.progress', $user) }}"
                                    class="space-y-3"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <p class="text-xs font-black uppercase tracking-wider text-gray-400">
                                        Edit Progress
                                    </p>

                                    <div>
                                        <label class="mb-1 block text-xs font-bold text-gray-600">
                                            XP
                                        </label>

                                        <input
                                            type="number"
                                            name="xp"
                                            min="0"
                                            max="999999"
                                            value="{{ $user->ecoProfile?->xp ?? 0 }}"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                            required
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-xs font-bold text-gray-600">
                                            Level
                                        </label>

                                        <input
                                            type="number"
                                            name="level"
                                            min="1"
                                            max="999"
                                            value="{{ $user->ecoProfile?->level ?? 1 }}"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                            required
                                        >
                                    </div>

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-green-800 px-4 py-3 text-xs font-black text-white transition hover:bg-green-900"
                                    >
                                        Simpan Progress
                                    </button>

                                </form>

                            </div>

                        </details>

                    </div>

                </div>

            @empty

                <div class="px-5 py-12 text-center">

                    <div class="text-4xl">
                        🔎
                    </div>

                    <p class="mt-3 font-bold text-gray-700">
                        User tidak ditemukan
                    </p>

                    <p class="mt-1 text-sm text-gray-400">
                        Coba gunakan nama atau email yang berbeda.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="border-t border-gray-100 px-5 py-5">
                {{ $users->links() }}
            </div>
        @endif

    </div>

@endsection