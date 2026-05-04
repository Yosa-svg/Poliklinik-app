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
use App\Http\Controllers\Pasien\RiwayatController;
use App\Http\Controllers\Pasien\PembayaranController as PasienPembayaranController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'dokter' => redirect()->route('dokter.dashboard'),
            default  => redirect()->route('pasien.dashboard'),
        };
    }
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

    // Export Excel
    Route::get('/jadwal-periksa/export/excel', [JadwalPeriksaController::class, 'export'])->name('jadwal-periksa.export');
    Route::get('/riwayat-pasien/export/excel', [JadwalPeriksaController::class, 'exportRiwayat'])->name('riwayat-pasien.export');
});

// ─── Pasien ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pasienDashboard'])->name('dashboard');

    Route::resource('poli', PoliController::class)->only(['index', 'show']);
    Route::resource('daftar-poli', DaftarPoliController::class)->only(['index', 'create', 'store']);
    Route::resource('periksa', PasienPeriksaController::class)->only(['index', 'show']);

    // Fitur 3: Riwayat Pendaftaran
    Route::resource('riwayat', RiwayatController::class)->only(['index', 'show']);

    // Fitur 6: Pembayaran
    Route::get('/pembayaran', [PasienPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{periksa}/upload', [PasienPembayaranController::class, 'upload'])->name('pembayaran.upload');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::resource('poliklinik', PoliklinikController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

    // Fitur 5: Export Excel (Admin)
    Route::get('/dokter/export/excel', [DokterController::class, 'export'])->name('dokter.export');
    Route::get('/pasien/export/excel', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('/obat/export/excel', [ObatController::class, 'export'])->name('obat.export');

    // Fitur 6: Verifikasi Pembayaran (Admin)
    Route::get('/admin/pembayaran', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::post('/admin/pembayaran/{periksa}/konfirmasi', [AdminPembayaranController::class, 'konfirmasi'])->name('admin.pembayaran.konfirmasi');
    Route::post('/admin/pembayaran/{periksa}/tolak', [AdminPembayaranController::class, 'tolak'])->name('admin.pembayaran.tolak');
});