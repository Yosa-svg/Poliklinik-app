<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;

class RiwayatController extends Controller
{
    /**
     * Show all registration history for the logged-in patient,
     * newest first, with eager loading to avoid N+1 queries.
     */
    public function index()
    {
        $riwayat = DaftarPoli::where('id_pasien', auth()->id())
            ->with([
                'jadwalPeriksa.dokter.poli',
                'periksa.detailPeriksa.obat',
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('pasien.riwayat.index', compact('riwayat'));
    }

    /**
     * Show detail of a single registration (only if already examined).
     */
    public function show(DaftarPoli $riwayat)
    {
        // Security: only own records
        if ($riwayat->id_pasien !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Must have examination record
        if (!$riwayat->periksa) {
            return redirect()->route('pasien.riwayat.index')
                ->with('message', 'Detail hanya tersedia setelah pemeriksaan selesai.')
                ->with('type', 'info');
        }

        $riwayat->load([
            'jadwalPeriksa.dokter.poli',
            'periksa.detailPeriksa.obat',
        ]);

        return view('pasien.riwayat.show', compact('riwayat'));
    }
}
