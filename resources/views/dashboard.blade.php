@extends('layouts.app')

@section('title', 'Dashboard - EcoScan AI')

@section('header')
    <p class="mt-3 text-xs uppercase">{{ now()->format('l, d F Y') }}</p>
    <h1 class="text-2xl font-bold">Selamat Datang!</h1>
    <p class="text-sm">Mari jaga lingkungan dengan memilah sampah.</p>
@endsection

@section('content')
    {{-- Kartu mulai scan --}}
    <div class="rounded-2xl bg-green-100 p-4">
        <h2 class="text-lg font-semibold text-green-800">Mulai Scan Sampah</h2>
        <p class="mb-3 text-xs text-green-800">Kenali jenis sampah menggunakan teknologi AI.</p>
        <a href="/scan" class="block rounded-lg bg-green-800 py-2 text-center text-sm font-semibold text-white">
            + Scan Sekarang
        </a>
    </div>

    {{-- Ringkasan aktivitas --}}
    <h2 class="text-lg font-semibold">Ringkasan Aktivitas</h2>
    <div class="grid grid-cols-2 gap-3">
        @foreach ([
            ['Total Scanning', $stats['total']],
            ['Sampah Organik', $stats['organik']],
            ['Sampah Anorganik', $stats['anorganik']],
            ['Sampah Lainnya', $stats['lainnya']],
        ] as [$label, $angka])
            <div class="rounded-xl bg-white p-4">
                <p class="text-2xl font-bold text-green-800">{{ $angka }}</p>
                <p class="text-xs text-gray-500">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    {{-- Aktivitas terakhir --}}
    <h2 class="text-lg font-semibold">Aktivitas Terakhir</h2>
    <div class="space-y-2">
        @foreach ($recent as $item)
            <div class="flex items-center justify-between rounded-xl bg-white p-4">
                <span class="font-semibold">{{ $item['nama'] }}</span>
                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                    {{ $item['kategori'] }}
                </span>
            </div>
        @endforeach
    </div>
@endsection