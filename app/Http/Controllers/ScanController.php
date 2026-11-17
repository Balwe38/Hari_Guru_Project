<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\WasteType;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120', // maks 5 MB
        ]);

        $path = $request->file('image')->store('scans', 'public');

        [$wasteType, $confidence] = $this->predict($path);

        $scan = Scan::create([
            'waste_type_id' => $wasteType?->id,
            'image_path'    => $path,
            'confidence'    => $confidence,
        ]);

        return redirect()->route('scan.show', $scan);
    }

    public function show(Scan $scan)
    {
        $scan->load('wasteType.category');

        return view('hasil', compact('scan'));
    }

    /**
     * SEMENTARA: hasil acak. Nanti bagian ini diganti
     * dengan panggilan ke API model AI (ml-service).
     */
    private function predict(string $path): array
    {
        return [
            WasteType::inRandomOrder()->first(),
            rand(8000, 9900) / 100,
        ];
    }
}