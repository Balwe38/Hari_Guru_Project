<?php

namespace App\Http\Controllers;

use App\Models\Scan;

class AdminScanController extends Controller
{
    public function index()
    {
        $scans = Scan::with(['user', 'wasteType'])
            ->latest()
            ->paginate(20);

        return view('admin.scans.index', compact('scans'));
    }

    public function destroy(Scan $scan)
    {
        $scan->delete();

        return back()->with(
            'success',
            'Data scan berhasil dihapus.'
        );
    }
}
