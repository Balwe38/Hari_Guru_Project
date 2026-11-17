@extends('layouts.app')

@section('title', 'Hasil Scan - EcoScan AI')

@section('header')
    <h1 class="mt-3 text-2xl font-bold">Hasil Scan</h1>
@endsection

@section('content')
    <div class="rounded-2xl bg-white p-4">
        <img src="{{ asset('storage/' . $scan->image_path) }}" class="mx-auto max-h-64 rounded-lg" alt="Gambar sampah">

        @if ($scan->wasteType)
            <h2 class="mt-4 text-xl font-bold text-green-800">{{ $scan->wasteType->name }}</h2>
            <span class="mt-1 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                {{ $scan->wasteType->category->name }}
            </span>
            <p class="mt-3 text-sm text-gray-500">Tingkat keyakinan AI: {{ $scan->confidence }}%</p>

            @if ($scan->wasteType->disposal_tip)
                <div class="mt-3 rounded-lg bg-green-100 p-3 text-sm text-green-900">
                    <p class="font-semibold">Cara membuang</p>
                    <p>{{ $scan->wasteType->disposal_tip }}</p>
                </div>
            @endif
        @else
            <p class="mt-4 text-sm">Jenis sampah belum dikenali.</p>
        @endif
    </div>

    <a href="{{ route('scan') }}" class="block rounded-lg bg-green-800 py-3 text-center font-semibold text-white">
        Scan Lagi
    </a>
    <a href="{{ route('dashboard') }}" class="block text-center text-sm text-green-800">Kembali ke Dashboard</a>
@endsection