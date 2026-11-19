<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\WasteType;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    private const MIN_CONFIDENCE = 80;
    private const MAX_SIDE = 600;

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

        $file = $request->file('image');
        $hash = sha1_file($file->getRealPath());
        $userId = $request->user()->id;

        $duplicate = Scan::where('user_id', $userId)
            ->where('image_hash', $hash)
            ->exists();

        $imageData = $this->toDataUri($file->getRealPath());
        $confidence = $request->input('confidence');
        $wasteType = null;

        if ($request->filled('label') && $confidence >= self::MIN_CONFIDENCE) {
            $wasteType = WasteType::where('label', $request->input('label'))->first();
        }

        $scan = Scan::create([
            'user_id'       => $userId,
            'waste_type_id' => $wasteType?->id,
            'image_path'    => null,
            'image_data'    => $imageData,
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
        abort_unless($scan->user_id === auth()->id(), 403);

        $scan->load('wasteType.category');

        return view('hasil', compact('scan'));
    }

    /** Kecilkan foto lalu ubah jadi data URI JPEG supaya bisa disimpan di database. */
    private function toDataUri(string $path): ?string
    {
        $info = @getimagesize($path);
        if (! $info) {
            return null;
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            default        => false,
        };
        if (! $src) {
            return null;
        }

        // Putar sesuai orientasi kamera HP (kalau ada)
        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $orientation = @exif_read_data($path)['Orientation'] ?? 1;
            $angle = match ($orientation) { 3 => 180, 6 => -90, 8 => 90, default => 0 };
            if ($angle !== 0 && ($rotated = imagerotate($src, $angle, 0))) {
                $src = $rotated;
            }
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $scale = min(1, self::MAX_SIDE / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $canvas = imagecreatetruecolor($nw, $nh);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        ob_start();
        imagejpeg($canvas, null, 75);
        $jpeg = ob_get_clean();

        return 'data:image/jpeg;base64,' . base64_encode($jpeg);
    }
}