@extends('layouts.app')

@section('title', 'Dashboard - EcoScan AI')

@section('header')
    <p class="text-xs uppercase">{{ now()->format('l, d F Y') }}</p>
    <h1 class="text-2xl font-bold sm:text-3xl lg:text-4xl">Selamat Datang!</h1>
    <p class="text-sm sm:text-base">Mari jaga lingkungan dengan memilah sampah.</p>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Kolom kiri: scan + ringkasan --}}
        <div class="space-y-6 lg:col-span-2">

            <div class="flex flex-col gap-4 rounded-2xl bg-green-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-green-800 sm:text-xl">Mulai Scan Sampah</h2>
                    <p class="text-xs text-green-800 sm:text-sm">Kenali jenis sampah menggunakan teknologi AI.</p>
                </div>
                <a href="{{ route('scan') }}"
                   class="rounded-lg bg-green-800 px-6 py-3 text-center text-sm font-semibold text-white hover:bg-green-700">
                    + Scan Sekarang
                </a>
            </div>

            <div>
                <h2 class="mb-3 text-lg font-semibold">Ringkasan Aktivitas</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    @foreach ([
                        ['Total Scanning', $stats['total']],
                        ['Sampah Organik', $stats['organik']],
                        ['Sampah Anorganik', $stats['anorganik']],
                        ['Sampah Lainnya', $stats['lainnya']],
                    ] as [$label, $angka])
                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            <p class="text-2xl font-bold text-green-800 sm:text-3xl">{{ $angka }}</p>
                            <p class="text-xs text-gray-500 sm:text-sm">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kolom kanan: aktivitas terakhir --}}
        <aside>
            <h2 class="mb-3 text-lg font-semibold">Aktivitas Terakhir</h2>
            <div class="space-y-2">
                @forelse ($recent as $item)
                    <div class="flex items-center justify-between rounded-xl bg-white p-4 shadow-sm">
                        <span class="font-semibold">{{ $item['nama'] }}</span>
                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                            {{ $item['kategori'] }}
                        </span>
                    </div>
                @empty
                    <p class="rounded-xl bg-white p-4 text-sm text-gray-500 shadow-sm">
                        Belum ada scan. Coba scan sampah pertamamu!
                    </p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection