@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-emerald-600">
                        ADMIN PANEL
                    </p>
                    <h1 class="mt-1 text-3xl font-black text-slate-900">
                        Scan Management
                    </h1>
                    <p class="mt-2 text-sm text-slate-500">
                        Kelola dan pantau seluruh aktivitas scan user.
                    </p>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
                    ← Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total Scan</p>
                <p class="mt-1 text-3xl font-black text-slate-900">
                    {{ $scans->total() }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Halaman</p>
                <p class="mt-1 text-3xl font-black text-slate-900">
                    {{ $scans->currentPage() }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Scan Ditampilkan</p>
                <p class="mt-1 text-3xl font-black text-slate-900">
                    {{ $scans->count() }}
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200">
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                Scan
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                User
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                Sampah
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                Confidence
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                XP
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                Waktu
                            </th>
                            <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($scans as $scan)
                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($scan->image_data)
                                            <img
                                                src="{{ $scan->image_data }}"
                                                alt="Scan"
                                                class="h-14 w-14 rounded-xl object-cover ring-1 ring-slate-200"
                                            >
                                        @elseif($scan->image_path)
                                            <img
                                                src="{{ asset('storage/' . $scan->image_path) }}"
                                                alt="Scan"
                                                class="h-14 w-14 rounded-xl object-cover ring-1 ring-slate-200"
                                            >
                                        @else
                                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-slate-100 text-xl">
                                                📷
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-bold text-slate-800">
                                                #{{ $scan->id }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                Scan ID
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @if($scan->user)
                                        <p class="font-bold text-slate-800">
                                            {{ $scan->user->name }}
                                        </p>
                                        <p class="text-xs text-slate-400">
                                            {{ $scan->user->email }}
                                        </p>
                                    @else
                                        <span class="text-sm text-slate-400">
                                            User tidak ditemukan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if($scan->wasteType)
                                        <p class="font-bold text-slate-800">
                                            {{ $scan->wasteType->name }}
                                        </p>
                                    @else
                                        <span class="text-sm text-slate-400">
                                            Tidak diketahui
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if($scan->confidence !== null)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-sm font-bold text-emerald-700">
                                            {{ number_format((float) $scan->confidence, 1) }}%
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <span class="font-black text-amber-600">
                                        +{{ $scan->xp_earned ?? 0 }} XP
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $scan->created_at?->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $scan->created_at?->format('H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <form
                                        action="{{ route('admin.scans.destroy', $scan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus data scan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-xl bg-red-50 px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="text-4xl">📭</div>
                                    <p class="mt-3 font-bold text-slate-700">
                                        Belum ada data scan
                                    </p>
                                    <p class="mt-1 text-sm text-slate-400">
                                        Aktivitas scan user akan muncul di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($scans->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $scans->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection