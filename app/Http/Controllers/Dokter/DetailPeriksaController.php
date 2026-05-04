<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $details  = $periksa->detailPeriksa()->with('obat')->get();
        $allObats = Obat::orderBy('nama_obat')->get();

        return view('dokter.periksa.detail', compact('periksa', 'details', 'allObats'));
    }

    /**
     * Add medicine to prescription with stock validation.
     * Uses DB transaction to safely decrement stock.
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

        // Check if medicine already added to this prescription
        $alreadyAdded = DetailPeriksa::where('id_periksa', $periksa->id)
            ->where('id_obat', $request->id_obat)
            ->exists();

        if ($alreadyAdded) {
            return redirect()->back()
                ->with('message', 'Obat ini sudah ada di resep!')
                ->with('type', 'warning');
        }

        // Execute inside a DB transaction for data safety
        try {
            DB::transaction(function () use ($request, $periksa) {
                // Lock the row for update to prevent race conditions
                $obat = Obat::lockForUpdate()->findOrFail($request->id_obat);

                // Check stock availability
                if ($obat->stok <= 0) {
                    throw new \Exception("Stok obat '{$obat->nama_obat}' habis! Tidak dapat ditambahkan ke resep.");
                }

                // Create the prescription detail
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat'    => $obat->id,
                ]);

                // Decrement stock by 1
                $obat->decrement('stok');
            });

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', $e->getMessage())
                ->with('type', 'danger');
        }

        return redirect()->back()
            ->with('message', 'Obat berhasil ditambahkan ke resep dan stok dikurangi.')
            ->with('type', 'success');
    }

    /**
     * Remove medicine from prescription and restore stock.
     */
    public function destroy(DetailPeriksa $detailPeriksa)
    {
        // Verify ownership
        $dokter_id = auth()->user()->id;
        if ($detailPeriksa->periksa->daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($detailPeriksa) {
                $obat = $detailPeriksa->obat;

                // Delete the detail record
                $detailPeriksa->delete();

                // Restore stock
                if ($obat) {
                    $obat->increment('stok');
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Gagal menghapus obat dari resep: ' . $e->getMessage())
                ->with('type', 'danger');
        }

        return redirect()->back()
            ->with('message', 'Obat berhasil dihapus dari resep dan stok dikembalikan.')
            ->with('type', 'success');
    }
}
