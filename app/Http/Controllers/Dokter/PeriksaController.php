<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Events\QueueUpdated;
use Illuminate\Http\Request;

class PeriksaController extends Controller
{
    /**
     * Display list of pending examinations for dokter.
     */
    public function index($dokter = null)
    {
        $dokter_id = auth()->user()->id;

        // Get all registrations for this doctor's schedule with no examination yet
        $daftars = DaftarPoli::whereHas('jadwalPeriksa', function ($query) use ($dokter_id) {
            $query->where('id_dokter', $dokter_id);
        })
            ->with(['pasien', 'jadwalPeriksa', 'periksa'])
            ->orderBy('no_antrian')
            ->get();

        // Separate pending and completed
        $pending = $daftars->filter(fn($d) => !$d->periksa);
        $completed = $daftars->filter(fn($d) => $d->periksa);

        return view('dokter.periksa.index', compact('pending', 'completed'));
    }

    /**
     * Show examination form for a patient.
     */
    public function show($dokter, $periksaId)
    {
        // Binding manual untuk menghindari mismatch nama parameter vs tabel
        $daftarPoli = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli'])->findOrFail($periksaId);

        // 1. Pengecekan relasi
        if (!$daftarPoli->jadwalPeriksa) {
            abort(404, 'Data Jadwal Periksa tidak ditemukan.');
        }

        $dokter_id = auth()->user()->id;

        // 2. Pengecekan hak akses
        if ($daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Cari hasil pemeriksaan jika sudah ada
        $hasilPeriksa = \App\Models\Periksa::with('detailPeriksa.obat')
            ->where('id_daftar_poli', $daftarPoli->id)
            ->first();

        // 4. Kirim ke view
        return view('dokter.periksa.show', [
            'daftarPoli' => $daftarPoli,
            'periksa' => $hasilPeriksa,
            'dokter' => $dokter,
        ]);
    }
    /**
     * Store a new examination record.
     */
    public function store(Request $request, $dokter_id) // 👈 Ubah parameter di sini
    {
        // 1. Validasi input, pastikan id_daftar_poli ikut dikirim
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'catatan' => 'required|string|min:10',
            'biaya_periksa' => 'nullable|numeric|min:0',
        ]);

        // 2. Cari DaftarPoli secara manual berdasarkan input hidden
        $daftarPoli = \App\Models\DaftarPoli::findOrFail($request->id_daftar_poli);

        // 3. Verify this patient is registered with this doctor
        $auth_dokter_id = auth()->user()->id;
        if ($daftarPoli->jadwalPeriksa->id_dokter != $auth_dokter_id) {
            abort(403);
        }

        // 4. Check if already examined
        if ($daftarPoli->periksa) {
            return redirect()->back()->with('message', 'Pasien ini sudah diperiksa!')->with('type', 'warning');
        }

        // 5. Simpan Hasil Pemeriksaan
        $periksa = \App\Models\Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now()->toDateString(),
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa ?? 0,
        ]);

        // ─── Broadcast real-time antrian update ──────────────────────────────
        try {
            $noDilayani = $daftarPoli->no_antrian;
            \App\Events\QueueUpdated::dispatch($daftarPoli->id_jadwal, $noDilayani);
        } catch (\Throwable $e) {
            // Reverb might not be running — silently fail
        }

        // 6. Redirect kembali ke halaman show dengan 2 parameter (dokter & daftar_poli)
        return redirect()->route('dokter.periksa.show', [$auth_dokter_id, $daftarPoli->id])
            ->with('message', 'Pemeriksaan berhasil dicatat. Sekarang tambahkan resep obat.')
            ->with('type', 'success');
    }

    /**
     * Update examination record.
     */
    public function update(Request $request, $dokter_id, Periksa $periksa)
    {
        // Verify ownership (gunakan variabel baru agar tidak bentrok)
        $auth_dokter_id = auth()->user()->id;
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter != $auth_dokter_id) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'required|string|min:10',
            'biaya_periksa' => 'nullable|numeric|min:0',
        ]);

        $periksa->update([
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa ?? 0,
        ]);

        return redirect()->back()
            ->with('message', 'Pemeriksaan berhasil diupdate.')
            ->with('type', 'success');
    }

    /**
     * Edit examination form.
     */
    public function edit($dokter_id, Periksa $periksa)
    {
        // Verify ownership menggunakan auth()
        $auth_dokter_id = auth()->user()->id;
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter != $auth_dokter_id) {
            abort(403);
        }

        $daftarPoli = $periksa->daftarPoli;

        return view('dokter.periksa.edit', compact('periksa', 'daftarPoli'));
    }
}
