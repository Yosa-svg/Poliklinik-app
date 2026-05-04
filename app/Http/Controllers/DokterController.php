<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use App\Exports\DokterExport;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polis = Poli::all();
        return view('admin.dokter.create', compact('polis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'dokter',
        ]);

        return redirect()->route('dokter.index')
            ->with('message', 'Dokter berhasil ditambahkan.')
            ->with('type', 'success');
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
    public function edit(User $dokter)
    {
       $polis = Poli::all();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $dokter)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . 
            $dokter->id,
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => 'required|email|unique:users,email,' . $dokter->id,
            'password' => 'nullable|string|min:6',
        ]);

        $update_data = [
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
            'role' => 'dokter',
        ];

        if ($request->filled('password')) {
            $update_data['password'] = $request->password;
        }

        $dokter->update($update_data);

        return redirect()->route('dokter.index')
            ->with('message', 'Dokter berhasil diupdate.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $dokter)
    {
        $dokter->delete();
        
        return redirect()->route('dokter.index')
            ->with('message', 'Dokter berhasil dihapus.')
            ->with('type', 'success');
    }

    /**
     * Export doctor list to Excel.
     */
    public function export()
    {
        return (new DokterExport)->download();
    }
}
