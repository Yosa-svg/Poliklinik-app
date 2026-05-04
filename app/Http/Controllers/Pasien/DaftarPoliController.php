<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;

class DaftarPoliController extends Controller
{
    /**
     * Display a listing of patient's registrations.
     */
    public function index()
    {
        $daftars = DaftarPoli::where('id_pasien', auth()->user()->id)
            ->with(['jadwalPeriksa.dokter', 'jadwalPeriksa.dokter.poli', 'periksa'])
            ->orderByDesc('created_at')
            ->get();

        return view('pasien.daftar-poli.index', compact('daftars'));
    }

    /**
     * Show the form for registering in a clinic.
     */
    public function create()
    {
        // Check if patient already has active queue — if so, block registration
        $hasActiveQueue = DaftarPoli::where('id_pasien', auth()->user()->id)
            ->doesntHave('periksa')
            ->exists();

        $jadwals = JadwalPeriksa::where('status', 'aktif')
            ->whereNotNull('id_dokter')
            ->with(['dokter', 'dokter.poli'])
            ->get()
            ->groupBy(fn($item) => $item->dokter->poli->nama_poli ?? 'Tanpa Poli');

        return view('pasien.daftar-poli.create', compact('jadwals', 'hasActiveQueue'));
    }

    /**
     * Store a patient registration in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan'   => 'required|string',
        ]);

        // ─── CEGAH DAFTAR KE DUA POLI SEKALIGUS ────────────────────────────
        $hasActiveQueue = DaftarPoli::where('id_pasien', auth()->user()->id)
            ->doesntHave('periksa')
            ->exists();

        if ($hasActiveQueue) {
            return redirect()->back()
                ->with('message', 'Anda masih memiliki antrian aktif. Selesaikan pemeriksaan terlebih dahulu sebelum mendaftar ke poli lain.')
                ->with('type', 'warning');
        }

        // Check if already registered to the exact same schedule
        $alreadyRegistered = DaftarPoli::where('id_pasien', auth()->user()->id)
            ->where('id_jadwal', $request->id_jadwal)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->back()
                ->with('message', 'Anda sudah terdaftar untuk jadwal ini!')
                ->with('type', 'warning');
        }

        // Get next queue number for this schedule
        $nextQueue = (DaftarPoli::where('id_jadwal', $request->id_jadwal)->max('no_antrian') ?? 0) + 1;

        DaftarPoli::create([
            'id_jadwal'  => $request->id_jadwal,
            'id_pasien'  => auth()->user()->id,
            'keluhan'    => $request->keluhan,
            'no_antrian' => $nextQueue,
        ]);

        return redirect()->route('pasien.daftar-poli.index')
            ->with('message', "Registrasi berhasil! Nomor antrian Anda: {$nextQueue}")
            ->with('type', 'success');
    }

    /**
     * Display patient registration details.
     */
    public function show(DaftarPoli $daftarPoli)
    {
        // Only own registrations
        if ($daftarPoli->id_pasien != auth()->user()->id) {
            abort(403);
        }

        return view('pasien.daftar-poli.show', compact('daftarPoli'));
    }
}
