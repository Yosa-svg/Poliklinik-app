<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\JadwalPeriksa;

class PoliController extends Controller
{
    /**
     * Display a listing of clinics.
     */
    public function index()
    {
        $polis = Poli::all();
        return view('pasien.poli.index', compact('polis'));
    }

    /**
     * Show clinic details with available doctors.
     */
    public function show($id)
    {
        $poli = Poli::findOrFail($id);
        $dokters = \App\Models\User::where('role', 'dokter')
            ->where('id_poli', $id)
            ->get();
        $jadwals = JadwalPeriksa::whereIn('id_dokter', $dokters->pluck('id'))
            ->orderBy('hari')
            ->get();
        
        return view('pasien.poli.show', compact('poli', 'dokters', 'jadwals'));
    }
}
