<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Show all bills for the logged-in patient.
     * Only shows examinations that have been completed.
     */
    public function index()
    {
        $tagihans = Periksa::whereIn(
            'id_daftar_poli',
            DaftarPoli::where('id_pasien', auth()->id())->pluck('id')
        )
        ->with([
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksa.obat',
        ])
        ->orderByDesc('created_at')
        ->get();

        return view('pasien.pembayaran.index', compact('tagihans'));
    }

    /**
     * Upload payment proof image for a specific examination.
     */
    public function upload(Request $request, Periksa $periksa)
    {
        // Verify ownership
        if ($periksa->daftarPoli->id_pasien !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Cannot re-upload if already confirmed
        if ($periksa->status_bayar === 'lunas') {
            return redirect()->back()
                ->with('message', 'Pembayaran sudah dikonfirmasi sebagai lunas.')
                ->with('type', 'info');
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'bukti_bayar.required' => 'Foto bukti pembayaran wajib diupload.',
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format gambar: jpeg, jpg, png.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // Delete old file if exists
        if ($periksa->bukti_bayar && \Storage::disk('public')->exists($periksa->bukti_bayar)) {
            \Storage::disk('public')->delete($periksa->bukti_bayar);
        }

        // Store new file
        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

        $periksa->update([
            'bukti_bayar'  => $path,
            'status_bayar' => 'menunggu_verifikasi',
        ]);

        return redirect()->back()
            ->with('message', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.')
            ->with('type', 'success');
    }
}
