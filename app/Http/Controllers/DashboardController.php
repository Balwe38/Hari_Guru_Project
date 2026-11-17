<?php

namespace App\Http\Controllers;

use App\Models\Scan;

class DashboardController extends Controller
{
    public function index()
    {
        $hitung = fn ($kategori) => Scan::whereHas(
            'wasteType.category',
            fn ($q) => $q->where('name', $kategori)
        )->count();

        $stats = [
            'total'     => Scan::count(),
            'organik'   => $hitung('Organik'),
            'anorganik' => $hitung('Anorganik'),
            'lainnya'   => $hitung('Lainnya'),
        ];

        $recent = Scan::with('wasteType.category')->latest()->take(5)->get()
            ->map(fn ($s) => [
                'nama'     => $s->wasteType->name ?? 'Tidak dikenali',
                'kategori' => $s->wasteType->category->name ?? '-',
            ]);

        return view('dashboard', compact('stats', 'recent'));
    }
}