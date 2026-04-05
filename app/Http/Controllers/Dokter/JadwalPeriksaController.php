<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;

class JadwalPeriksaController extends Controller
{
    /**
     * Display a listing of doctor's schedules.
     */
    public function index()
    {
        $jadwalPeriksa = JadwalPeriksa::where('id_dokter', auth()->user()->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
        
        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksa'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    /**
     * Store a newly created schedule in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i,H:i:s',
            'jam_selesai' => 'required|date_format:H:i,H:i:s|after:jam_mulai',
        ]);

        JadwalPeriksa::create([
            'id_dokter' => auth()->user()->id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil ditambahkan!')
            ->with('type', 'success');
    }

    /**
     * Display the specified schedule.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the schedule.
     */
    public function edit(JadwalPeriksa $jadwalPeriksa)
    {
        // Only allowed to edit own schedules
        if ($jadwalPeriksa->id_dokter != auth()->user()->id) {
            abort(403);
        }

        // Rename to $jadwal agar konsisten dengan nama variabel di view
        $jadwal = $jadwalPeriksa;
        return view('dokter.jadwal-periksa.edit', compact('jadwal'));
    }

    /**
     * Update the specified schedule in database.
     */
    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        // Only allowed to edit own schedules
        if ($jadwalPeriksa->id_dokter != auth()->user()->id) {
            abort(403);
        }

        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i,H:i:s',
            'jam_selesai' => 'required|date_format:H:i,H:i:s|after:jam_mulai',
            'status'      => 'required|in:aktif,tidak aktif',
        ]);

        $jadwalPeriksa->update([
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status,
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil diperbaharui!')
            ->with('type', 'success');
    }

    /**
     * Remove the specified schedule from database.
     */
    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        // Only allowed to delete own schedules
        if ($jadwalPeriksa->id_dokter != auth()->user()->id) {
            abort(403);
        }
        
        $jadwalPeriksa->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil dihapus!')
            ->with('type', 'success');
    }
}
