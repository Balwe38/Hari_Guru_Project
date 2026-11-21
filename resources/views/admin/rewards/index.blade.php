@extends('layouts.app')

@section('title', 'Kelola Rewards - EcoScan AI')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                ← Kembali ke Admin Dashboard
            </a>

            <div class="mt-4 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">
                        🎁 Kelola Rewards
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Atur hadiah yang dapat ditukar menggunakan XP pengguna.
                    </p>
                </div>

                <details>
                    <summary
                        class="cursor-pointer list-none rounded-xl bg-emerald-600 px-5 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-emerald-700"
                    >
                        + Tambah Reward
                    </summary>

                    <div class="mt-3 rounded-2xl bg-white p-5 shadow-lg ring-1 ring-slate-200 md:absolute md:right-6 md:z-20 md:w-[420px]">
                        <h2 class="text-lg font-bold text-slate-900">
                            Tambah Reward Baru
                        </h2>

                        <form action="{{ route('admin.rewards.store') }}" method="POST" class="mt-4 space-y-4">
                            @csrf

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Nama Reward
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Contoh: Voucher Eco Shop"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                    required
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Deskripsi
                                </label>

                                <textarea
                                    name="description"
                                    rows="3"
                                    placeholder="Deskripsi reward..."
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700">
                                        Icon
                                    </label>

                                    <input
                                        type="text"
                                        name="icon"
                                        value="🎁"
                                        maxlength="16"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-center text-lg outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700">
                                        Harga XP
                                    </label>

                                    <input
                                        type="number"
                                        name="cost_xp"
                                        min="0"
                                        value="100"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                        required
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Stock
                                </label>

                                <input
                                    type="number"
                                    name="stock"
                                    min="0"
                                    placeholder="Kosongkan = tanpa batas"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                            </div>

                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    checked
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                >
                                Aktifkan reward
                            </label>

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700"
                            >
                                Simpan Reward
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>


        {{-- Flash Messages --}}
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


        {{-- Validation --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Ada data yang perlu diperbaiki:</p>

                <ul class="mt-2 list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Reward Cards --}}
        @if($rewards->count())

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                @foreach($rewards as $reward)

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">

                        {{-- Top --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-3xl">
                                    {{ $reward->icon }}
                                </div>

                                <div>
                                    <h2 class="font-bold text-slate-900">
                                        {{ $reward->name }}
                                    </h2>

                                    @if($reward->is_active)
                                        <span class="mt-1 inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold uppercase text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="mt-1 inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase text-slate-500">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>

                            </div>

                            <div class="rounded-xl bg-amber-50 px-3 py-2 text-center">
                                <p class="text-[10px] font-medium text-amber-600">
                                    HARGA
                                </p>

                                <p class="font-bold text-amber-700">
                                    ⭐ {{ number_format($reward->cost_xp) }}
                                </p>
                            </div>

                        </div>


                        {{-- Description --}}
                        <p class="mt-4 min-h-[48px] text-sm leading-6 text-slate-600">
                            {{ $reward->description ?: 'Tidak ada deskripsi.' }}
                        </p>


                        {{-- Stats --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">
                                    Stock
                                </p>

                                <p class="mt-1 font-bold text-slate-700">
                                    {{ $reward->stock === null ? '∞ Tanpa batas' : number_format($reward->stock) }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">
                                    Redeemed
                                </p>

                                <p class="mt-1 font-bold text-slate-700">
                                    👥 {{ number_format($reward->redemptions_count) }}
                                </p>
                            </div>

                        </div>


                        {{-- Stock Status --}}
                        @if($reward->isOutOfStock())

                            <div class="mt-4 rounded-xl bg-red-50 px-3 py-2 text-center text-xs font-semibold text-red-600">
                                ⚠️ Stock habis
                            </div>

                        @endif


                        {{-- Edit --}}
                        <details class="mt-4">

                            <summary
                                class="cursor-pointer list-none rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-200"
                            >
                                ✏️ Edit Reward
                            </summary>

                            <form
                                action="{{ route('admin.rewards.update', $reward) }}"
                                method="POST"
                                class="mt-3 space-y-4 rounded-xl bg-slate-50 p-4"
                            >
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                        Nama
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ $reward->name }}"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                        Deskripsi
                                    </label>

                                    <textarea
                                        name="description"
                                        rows="2"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                    >{{ $reward->description }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-3">

                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-slate-600">
                                            Icon
                                        </label>

                                        <input
                                            type="text"
                                            name="icon"
                                            value="{{ $reward->icon }}"
                                            maxlength="16"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-center text-lg"
                                            required
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-slate-600">
                                            Harga XP
                                        </label>

                                        <input
                                            type="number"
                                            name="cost_xp"
                                            min="0"
                                            value="{{ $reward->cost_xp }}"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                            required
                                        >
                                    </div>

                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                        Stock
                                    </label>

                                    <input
                                        type="number"
                                        name="stock"
                                        min="0"
                                        value="{{ $reward->stock }}"
                                        placeholder="Kosongkan = tanpa batas"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                    >
                                </div>

                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        {{ $reward->is_active ? 'checked' : '' }}
                                        class="rounded border-slate-300"
                                    >
                                    Reward aktif
                                </label>

                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                                >
                                    Simpan Perubahan
                                </button>

                            </form>

                        </details>


                        {{-- Actions --}}
                        <div class="mt-3 flex gap-2">

                            {{-- Toggle --}}
                            <form
                                action="{{ route('admin.rewards.toggle', $reward) }}"
                                method="POST"
                                class="flex-1"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                                >
                                    {{ $reward->is_active ? '🔴 Nonaktifkan' : '🟢 Aktifkan' }}
                                </button>
                            </form>


                            {{-- Delete --}}
                            @if($reward->redemptions_count === 0)

                                <form
                                    action="{{ route('admin.rewards.destroy', $reward) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-xl bg-red-50 px-3 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-100"
                                    >
                                        🗑️
                                    </button>
                                </form>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- Empty --}}
            <div class="rounded-2xl bg-white px-6 py-16 text-center shadow-sm ring-1 ring-slate-100">

                <div class="text-5xl">
                    🎁
                </div>

                <h2 class="mt-4 text-xl font-bold text-slate-900">
                    Belum ada reward
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Tambahkan reward pertama yang bisa ditukar menggunakan XP.
                </p>

            </div>

        @endif

    </div>
</div>
@endsection