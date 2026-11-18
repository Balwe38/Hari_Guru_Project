<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\WasteType;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    private const MIN_CONFIDENCE = 80; // di bawah ini dianggap "tidak dikenali"

    public function index()
    {
        return view('scan');
    }

    public function store(Request $request, GamificationService $gamification)
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'label'      => 'nullable|string|max:50',
            'confidence' => 'nullable|numeric|min:0|max:100',
        ]);

        $file       = $request->file('image');
        $hash       = sha1_file($file->getRealPath());
        $duplicate  = Scan::where('image_hash', $hash)->exists();
        $path       = $file->store('scans', 'public');
        $confidence = $request->input('confidence');
        $wasteType  = null;

        if ($request->filled('label') && $confidence >= self::MIN_CONFIDENCE) {
            $wasteType = WasteType::where('label', $request->input('label'))->first();
        }

        $scan = Scan::create([
            'waste_type_id' => $wasteType?->id,
            'image_path'    => $path,
            'confidence'    => $confidence,
            'image_hash'    => $hash,
        ]);

        $reward = $gamification->awardScan($scan, $duplicate);

        return redirect()
            ->route('scan.show', $scan)
            ->with('gamification', $reward);
    }

    public function show(Scan $scan)
    {
        $scan->load('wasteType.category');

        return view('hasil', compact('scan'));
    }
}
