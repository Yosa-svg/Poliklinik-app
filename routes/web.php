<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaController;
use App\Http\Controllers\Dokter\DetailPeriksaController;
use App\Http\Controllers\Pasien\PoliController;
use App\Http\Controllers\Pasien\DaftarPoliController;
use App\Http\Controllers\Pasien\PeriksaController as PasienPeriksaController;

Route::get('/', function () {
    // Jika user sudah login, cek role-nya dan arahkan ke dashboard yang sesuai
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'dokter' => redirect()->route('dokter.dashboard'),
            default  => redirect()->route('pasien.dashboard'),
        };
    }
    
    // Jika belum login, baru lempar ke form login
    return redirect()->route('login');
});

// ─── Auth (hanya untuk tamu / belum login) ───────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Dokter ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:dokter'])->group(function () {
    Route::get('/dokter/dashboard', [DashboardController::class, 'dokterDashboard'])->name('dokter.dashboard');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class)->except('show');
    Route::resource('dokter.periksa', PeriksaController::class)->only(['index', 'show', 'store', 'edit', 'update']);
    Route::resource('dokter.periksa.detail', DetailPeriksaController::class)->only(['index', 'store', 'destroy']);
});

// ─── Pasien (PINDAHKAN KE ATAS ADMIN) ────────────────────────────────────────
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pasienDashboard'])->name('dashboard');
    
    // Pastikan parameter pertamanya 'poli', BUKAN 'pasien.poli'
    Route::resource('poli', PoliController::class)->only(['index', 'show']);
    
    // Pastikan parameter pertamanya 'daftar-poli', BUKAN 'pasien.daftar-poli'
    Route::resource('daftar-poli', DaftarPoliController::class)->only(['index', 'create', 'store']);
    
    // Pastikan parameter pertamanya 'periksa', BUKAN 'pasien.periksa'
    Route::resource('periksa', PasienPeriksaController::class)->only(['index', 'show']);
});

// ─── Admin (PINDAHKAN KE PALING BAWAH) ───────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('poliklinik', PoliklinikController::class);
    Route::resource('dokter', DokterController::class); 
    Route::resource('pasien', PasienController::class); // Sekarang tidak akan menelan /pasien/dashboard
    Route::resource('obat', ObatController::class);
});