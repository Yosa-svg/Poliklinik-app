<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->with('poli')->get();
        return view('admin.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $polis = Poli::all();
        return view('admin.pasien.create', compact('polis'));
    }

    /**
     * Store a newly created patient in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'nullable|exists:poli,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Generate medical record number (no_rm) - format: YYYYMM-XXX
        $year_month = date('Ym');
        $count = User::where('role', 'pasien')
            ->where('no_rm', 'like', $year_month . '%')
            ->count();
        $no_rm = $year_month . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        User::create([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'no_rm' => $no_rm,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'pasien',
        ]);

        return redirect()->route('pasien.index')
            ->with('message', 'Pasien berhasil ditambahkan.')
            ->with('type', 'success');
    }

    /**
     * Display the specified patient.
     */
    public function show(User $pasien)
    {
        return view('admin.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the patient.
     */
    public function edit(User $pasien)
    {
        $polis = Poli::all();
        return view('admin.pasien.edit', compact('pasien', 'polis'));
    }

    /**
     * Update the specified patient in database.
     */
    public function update(Request $request, User $pasien)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $pasien->id,
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'nullable|exists:poli,id',
            'email' => 'required|email|unique:users,email,' . $pasien->id,
        ]);

        $update_data = [
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6|confirmed']);
            $update_data['password'] = $request->password;
        }

        $pasien->update($update_data);

        return redirect()->route('pasien.index')
            ->with('message', 'Pasien berhasil diupdate.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified patient from database.
     */
    public function destroy(User $pasien)
    {
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('message', 'Pasien berhasil dihapus.')
            ->with('type', 'success');
    }
}
