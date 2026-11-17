<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

public function run(): void
{
    $organik   = WasteCategory::create(['name' => 'Organik']);
    $anorganik = WasteCategory::create(['name' => 'Anorganik']);
    $lainnya   = WasteCategory::create(['name' => 'Lainnya']);

    $organik->wasteTypes()->create([
        'label' => 'biological', 'name' => 'Sisa Makanan/Organik',
        'disposal_tip' => 'Bisa dijadikan kompos.',
    ]);

    foreach ([
        ['plastic', 'Plastik'], ['glass', 'Kaca'], ['metal', 'Logam'],
        ['paper', 'Kertas'], ['cardboard', 'Kardus'],
    ] as [$label, $name]) {
        $anorganik->wasteTypes()->create([
            'label' => $label, 'name' => $name,
            'disposal_tip' => 'Bersihkan, lalu setor ke bank sampah atau tempat daur ulang.',
        ]);
    }

    foreach ([
        ['battery', 'Baterai'], ['clothes', 'Pakaian'],
        ['shoes', 'Sepatu'], ['trash', 'Residu'],
    ] as [$label, $name]) {
        $lainnya->wasteTypes()->create([
            'label' => $label, 'name' => $name,
            'disposal_tip' => 'Buang di tempat khusus atau tempat sampah residu.',
        ]);
    }
}
}
