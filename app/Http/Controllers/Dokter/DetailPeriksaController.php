<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use Illuminate\Http\Request;

class DetailPeriksaController extends Controller
{
    /**
     * Show prescription management page for examination.
     */
    public function index(Periksa $periksa)
    {
        // Verify ownership
        $dokter_id = auth()->user()->id;
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        $details = $periksa->detailPeriksa()->with('obat')->get();
        $allObats = Obat::all();

        return view('dokter.periksa.detail', compact('periksa', 'details', 'allObats'));
    }

    /**
     * Add medicine to prescription.
     */
    public function store(Request $request, Periksa $periksa)
    {
        // Verify ownership
        $dokter_id = auth()->user()->id;
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        $request->validate([
            'id_obat' => 'required|exists:obat,id',
        ]);

        // Check if medicine already added
        $check = DetailPeriksa::where('id_periksa', $periksa->id)
            ->where('id_obat', $request->id_obat)
            ->exists();

        if ($check) {
            return redirect()->back()
                ->with('message', 'Obat ini sudah ada di resep!')
                ->with('type', 'warning');
        }

        DetailPeriksa::create([
            'id_periksa' => $periksa->id,
            'id_obat' => $request->id_obat,
        ]);

        return redirect()->back()
            ->with('message', 'Obat berhasil ditambahkan ke resep.')
            ->with('type', 'success');
    }

    /**
     * Remove medicine from prescription.
     */
    public function destroy(DetailPeriksa $detailPeriksa)
    {
        // Verify ownership
        $dokter_id = auth()->user()->id;
        if ($detailPeriksa->periksa->daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        $detail_periksa_id = $detailPeriksa->id;
        $detailPeriksa->delete();

        return redirect()->back()
            ->with('message', 'Obat berhasil dihapus dari resep.')
            ->with('type', 'success');
    }
}
