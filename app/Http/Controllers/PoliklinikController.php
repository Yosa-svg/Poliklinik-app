<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    /**
     * Display a listing of clinics.
     */
    public function index()
    {
        $polis = Poli::all();
        return view('admin.poliklinik.index', compact('polis'));
    }

    /**
     * Show the form for creating a new clinic.
     */
    public function create()
    {
        return view('admin.poliklinik.create');
    }

    /**
     * Store a newly created clinic in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:255|unique:poli,nama_poli',
            'keterangan' => 'nullable|string',
        ]);

        Poli::create([
            'nama_poli' => $request->nama_poli,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('poliklinik.index')
            ->with('message', 'Poliklinik berhasil ditambahkan.')
            ->with('type', 'success');
    }

    /**
     * Display the specified clinic.
     */
    public function show(Poli $poli)
    {
        return view('admin.poliklinik.show', compact('poli'));
    }

    /**
     * Show the form for editing the clinic.
     */
    public function edit(Poli $poli)
    {
        return view('admin.poliklinik.edit', compact('poli'));
    }

    /**
     * Update the specified clinic in database.
     */
    public function update(Request $request, Poli $poli)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:255|unique:poli,nama_poli,' . $poli->id,
            'keterangan' => 'nullable|string',
        ]);

        $poli->update([
            'nama_poli' => $request->nama_poli,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('poliklinik.index')
            ->with('message', 'Poliklinik berhasil diupdate.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified clinic from database.
     */
    public function destroy(Poli $poli)
    {
        $poli->delete();

        return redirect()->route('poliklinik.index')
            ->with('message', 'Poliklinik berhasil dihapus.')
            ->with('type', 'success');
    }
}
