<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index')->with([
            'obats' => $obats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */ 
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
        ]);

        return redirect()->route('obat.index')->with('status', 'obat-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Obat berhasil diupdate.')
            ->with('type', 'success');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Obat berhasil dihapus.')
            ->with('type', 'success');
    }
}
