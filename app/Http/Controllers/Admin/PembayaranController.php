<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Models\DaftarPoli;

class PembayaranController extends Controller
{
    /**
     * Show all bills waiting for verification, and confirmed ones.
     */
    public function index()
    {
        $menunggu = Periksa::where('status_bayar', 'menunggu_verifikasi')
            ->with([
                'daftarPoli.pasien',
                'daftarPoli.jadwalPeriksa.dokter.poli',
                'detailPeriksa.obat',
            ])
            ->orderByDesc('updated_at')
            ->get();

        $lunas = Periksa::where('status_bayar', 'lunas')
            ->with([
                'daftarPoli.pasien',
                'daftarPoli.jadwalPeriksa.dokter.poli',
            ])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        return view('admin.pembayaran.index', compact('menunggu', 'lunas'));
    }

    /**
     * Confirm a payment as 'lunas'.
     */
    public function konfirmasi(Periksa $periksa)
    {
        if ($periksa->status_bayar !== 'menunggu_verifikasi') {
            return redirect()->back()
                ->with('message', 'Status pembayaran tidak valid untuk dikonfirmasi.')
                ->with('type', 'warning');
        }

        $periksa->update(['status_bayar' => 'lunas']);

        return redirect()->back()
            ->with('message', "Pembayaran pasien {$periksa->daftarPoli->pasien->name} berhasil dikonfirmasi sebagai LUNAS.")
            ->with('type', 'success');
    }

    /**
     * Reject / reset a payment back to 'belum_bayar'.
     */
    public function tolak(Periksa $periksa)
    {
        $periksa->update([
            'status_bayar' => 'belum_bayar',
            'bukti_bayar'  => null,
        ]);

        return redirect()->back()
            ->with('message', 'Bukti pembayaran ditolak. Pasien diminta upload ulang.')
            ->with('type', 'warning');
    }
}
