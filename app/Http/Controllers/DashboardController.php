<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy dulu, nanti diganti data dari database
        $stats = [
            'total'     => 128,
            'organik'   => 45,
            'anorganik' => 62,
            'lainnya'   => 21,
        ];

        $recent = [
            ['nama' => 'Kulit Pisang',   'kategori' => 'Organik'],
            ['nama' => 'Botol Plastik',  'kategori' => 'Anorganik'],
            ['nama' => 'Baterai Bekas',  'kategori' => 'Lainnya'],
        ];

        return view('dashboard', compact('stats', 'recent'));
    }
}