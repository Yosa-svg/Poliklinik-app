<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman form login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    // Alias agar cocok dengan route yang memanggil showLoginForm
    public function showLoginForm()
    {
        return $this->showLogin();
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'admin'  => redirect()->route('admin.dashboard'),
                'dokter' => redirect()->route('dokter.dashboard'),
                default  => redirect()->route('pasien.dashboard'),
            };
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    /**
     * Tampilkan halaman form registrasi.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    // Alias agar cocok dengan route yang memanggil showRegisterForm
    public function showRegisterForm()
    {
        return $this->showRegister();
    }

    /**
     * Proses registrasi pasien baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255', 
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'alamat'                => 'required|string|max:255',
            'no_hp'                 => 'required|string|max:20|unique:users',
            'no_ktp'                => 'required|string|max:20|unique:users',
        ]);

        // Generate nomor rekam medis: YYYYMM-XXX (urut per bulan)
        $prefix = date('Ym'); 
        $jumlahBulanIni = User::where('role', 'pasien')
            ->where('no_rm', 'like', $prefix . '-%')
            ->count();
        $urutan = str_pad($jumlahBulanIni + 1, 3, '0', STR_PAD_LEFT); 
        $no_rm = $prefix . '-' . $urutan; 

        // Simpan ke database dengan password sudah ter-hash Bcrypt
        User::create([
            'name'     => $request->name, 
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'no_ktp'   => $request->no_ktp,
            'no_rm'    => $no_rm,
            'role'     => 'pasien',
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
