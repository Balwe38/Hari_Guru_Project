@extends('layouts.app')

@section('title', 'Hasil Scan - EcoScan AI')

@section('header')
    <h1 class="text-2xl font-bold sm:text-3xl lg:text-4xl">Hasil Scan</h1>
@endsection

@section('content')
    <div class="mx-auto max-w-4xl space-y-4">
        <div class="grid gap-6 rounded-2xl bg-white p-5 shadow-sm sm:p-6 md:grid-cols-2">

            <img src="{{ asset('storage/' . $scan->image_path) }}"
                 class="mx-auto max-h-80 w-full rounded-lg object-contain" alt="Gambar sampah">

            <div>
                @if ($scan->wasteType)
                    <h2 class="text-2xl font-bold text-green-800">{{ $scan->wasteType->name }}</h2>
                    <span class="mt-2 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                        {{ $scan->wasteType->category->name }}
                    </span>
                    <p class="mt-3 text-sm text-gray-500">Tingkat keyakinan AI: {{ $scan->confidence }}%</p>

                    @if ($scan->wasteType->disposal_tip)
                        <div class="mt-4 rounded-lg bg-green-100 p-4 text-sm text-green-900">
                            <p class="font-semibold">Cara membuang</p>
                            <p>{{ $scan->wasteType->disposal_tip }}</p>
                        </div>
                    @endif
                @else
                    <p class="text-sm">Jenis sampah belum dikenali.</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('scan') }}"
               class="flex-1 rounded-lg bg-green-800 py-3 text-center font-semibold text-white hover:bg-green-700">
                Scan Lagi
            </a>
            <a href="{{ route('dashboard') }}"
               class="flex-1 rounded-lg border border-green-800 py-3 text-center font-semibold text-green-800 hover:bg-green-50">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection