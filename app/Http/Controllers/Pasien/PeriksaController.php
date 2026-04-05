<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;

class PeriksaController extends Controller
{
    /**
     * Display patient's examination history.
     */
    public function index()
    {
        $periksas = Periksa::whereHas('daftarPoli', function ($query) {
            $query->where('id_pasien', auth()->user()->id);
        })
        ->with(['daftarPoli.jadwalPeriksa.dokter', 'daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksa.obat'])
        ->orderByDesc('tgl_periksa')
        ->get();

        return view('pasien.periksa.index', compact('periksas'));
    }

    /**
     * Display detailed examination result with prescription.
     */
    public function show(Periksa $periksa)
    {
        // Verify ownership
        if ($periksa->daftarPoli->id_pasien != auth()->user()->id) {
            abort(403);
        }

        $details = $periksa->detailPeriksa()->with('obat')->get();

        return view('pasien.periksa.show', compact('periksa', 'details'));
    }
}
