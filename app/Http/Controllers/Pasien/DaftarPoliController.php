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
            ->with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli', 'periksa'])
            ->orderByDesc('created_at')
            ->get();
        
        return view('pasien.daftar-poli.index', compact('daftars'));
    }

    /**
     * Show the form for registering in a clinic.
     */
    public function create()
    {
        $jadwals = JadwalPeriksa::where('id_dokter', '!=', null)
            ->with(['dokter', 'dokter.poli'])
            ->get()
            ->groupBy(function ($item) {
                return $item->dokter->poli->nama_poli ?? 'Tanpa Poli';
            });
        
        return view('pasien.daftar-poli.create', compact('jadwals'));
    }

    /**
     * Store a patient registration in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        // Check if already registered
        $check = DaftarPoli::where('id_pasien', auth()->user()->id)
            ->where('id_jadwal', $request->id_jadwal)
            ->exists();
        
        if ($check) {
            return redirect()->back()
                ->with('message', 'Anda sudah terdaftar untuk jadwal ini!')
                ->with('type', 'warning');
        }

        // Get queue number
        $queue = DaftarPoli::where('id_jadwal', $request->id_jadwal)
            ->max('no_antrian') ?? 0;

        DaftarPoli::create([
            'id_jadwal' => $request->id_jadwal,
            'id_pasien' => auth()->user()->id,
            'keluhan' => $request->keluhan,
            'no_antrian' => $queue + 1,
        ]);

        return redirect()->route('pasien.daftar-poli.index')
            ->with('message', 'Registrasi berhasil! Nomor antrian Anda tercatat.')
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
