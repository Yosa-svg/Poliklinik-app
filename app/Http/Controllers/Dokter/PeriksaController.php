<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Http\Request;

class PeriksaController extends Controller
{
    /**
     * Display list of pending examinations for dokter.
     */
    public function index()
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
    public function show(DaftarPoli $daftarPoli)
    {
        // Verify this patient is registered with this doctor
        $dokter_id = auth()->user()->id;
        if ($daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        // Get existing examination if any
        $periksa = $daftarPoli->periksa;

        return view('dokter.periksa.show', compact('daftarPoli', 'periksa'));
    }

    /**
     * Store a new examination record.
     */
    public function store(Request $request, DaftarPoli $daftarPoli)
    {
        // Verify this patient is registered with this doctor
        $dokter_id = auth()->user()->id;
        if ($daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        // Check if already examined
        if ($daftarPoli->periksa) {
            return redirect()->back()->with('message', 'Pasien ini sudah diperiksa!')->with('type', 'warning');
        }

        $request->validate([
            'catatan' => 'required|string|min:10',
            'biaya_periksa' => 'nullable|numeric|min:0',
        ]);

        $periksa = Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now()->toDateString(),
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa ?? 0,
        ]);

        return redirect()->route('dokter.periksa.show', $periksa->id)
            ->with('message', 'Pemeriksaan berhasil dicatat. Sekarang tambahkan resep obat.')
            ->with('type', 'success');
    }

    /**
     * Update examination record.
     */
    public function update(Request $request, Periksa $periksa)
    {
        // Verify ownership
        $dokter_id = auth()->user()->id;
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
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
    public function edit(Periksa $periksa)
    {
        // Verify ownership
        $dokter_id = auth()->user()->id;
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter != $dokter_id) {
            abort(403);
        }

        $daftarPoli = $periksa->daftarPoli;

        return view('dokter.periksa.edit', compact('periksa', 'daftarPoli'));
    }
}
