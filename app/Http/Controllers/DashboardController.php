<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\JadwalPeriksa;

class DashboardController extends Controller
{
    // ─── Admin Dashboard ──────────────────────────────────────────────────────
    public function adminDashboard()
    {
        try {
            $totalPoli = Poli::count();
            $totalDokter = User::where('role', 'dokter')->count();
            $totalPasien = User::where('role', 'pasien')->count();
            $totalPemeriksaan = Periksa::count();

            $lastMonthPatients = User::where('role', 'pasien')
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            $pemeriksaanByClinic = Poli::select('poli.id', 'poli.nama_poli')
                ->selectRaw('COUNT(periksa.id) as count')
                ->leftJoin('users as dokter', 'dokter.id_poli', '=', 'poli.id')
                ->leftJoin('jadwal_periksa', 'jadwal_periksa.id_dokter', '=', 'dokter.id')
                ->leftJoin('daftar_poli', 'daftar_poli.id_jadwal', '=', 'jadwal_periksa.id')
                ->leftJoin('periksa', 'periksa.id_daftar_poli', '=', 'daftar_poli.id')
                ->groupBy('poli.id', 'poli.nama_poli')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->map(fn($poli) => ['name' => $poli->nama_poli, 'count' => $poli->count]);

            $recentRegistrations = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            $recentExaminations = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            $pendingPayments = Periksa::where('status_bayar', 'menunggu_verifikasi')->count();

            return view('admin.dashboard', compact(
                'totalPoli', 'totalDokter', 'totalPasien', 'totalPemeriksaan',
                'lastMonthPatients', 'pemeriksaanByClinic', 'recentRegistrations',
                'recentExaminations', 'pendingPayments'
            ));
        } catch (\Exception $e) {
            return view('admin.dashboard', [
                'totalPoli' => 0, 'totalDokter' => 0, 'totalPasien' => 0,
                'totalPemeriksaan' => 0, 'lastMonthPatients' => 0,
                'pemeriksaanByClinic' => collect([]), 'recentRegistrations' => collect([]),
                'recentExaminations' => collect([]), 'pendingPayments' => 0,
            ]);
        }
    }

    // ─── Doctor Dashboard ────────────────────────────────────────────────────
    public function dokterDashboard()
    {
        try {
            $dokter = auth()->user();
            $jadwalIds = $dokter->jadwalPeriksa()->pluck('id')->toArray();

            $periksasThisMonth = Periksa::whereYear('tgl_periksa', date('Y'))
                ->whereMonth('tgl_periksa', date('m'))
                ->whereIn('id_daftar_poli', DaftarPoli::whereIn('id_jadwal', $jadwalIds)->pluck('id'))
                ->count();

            $pendingExaminations = DaftarPoli::whereIn('id_jadwal', $jadwalIds)
                ->doesntHave('periksa')
                ->count();

            $totalExaminations = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::whereIn('id_jadwal', $jadwalIds)->pluck('id')
            )->count();

            $revenueThisMonth = Periksa::whereYear('tgl_periksa', date('Y'))
                ->whereMonth('tgl_periksa', date('m'))
                ->whereIn('id_daftar_poli', DaftarPoli::whereIn('id_jadwal', $jadwalIds)->pluck('id'))
                ->sum('biaya_periksa') ?? 0;

            $commonComplaints = DaftarPoli::whereIn('id_jadwal', $jadwalIds)
                ->whereNotNull('keluhan')
                ->selectRaw('keluhan, COUNT(*) as count')
                ->groupBy('keluhan')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->pluck('count', 'keluhan');

            return view('dokter.dashboard', compact(
                'periksasThisMonth', 'pendingExaminations', 'totalExaminations',
                'revenueThisMonth', 'commonComplaints'
            ));
        } catch (\Exception $e) {
            return view('dokter.dashboard', [
                'periksasThisMonth' => 0, 'pendingExaminations' => 0,
                'totalExaminations' => 0, 'revenueThisMonth' => 0,
                'commonComplaints' => collect([])
            ]);
        }
    }

    // ─── Patient Dashboard ───────────────────────────────────────────────────
    public function pasienDashboard()
    {
        try {
            $pasien = auth()->user();

            $totalExaminations = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::where('id_pasien', $pasien->id)->pluck('id')
            )->count();

            $lastExamination = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::where('id_pasien', $pasien->id)->pluck('id')
            )->orderByDesc('tgl_periksa')->first();

            $doctorSpecialists = DaftarPoli::where('id_pasien', $pasien->id)
                ->with('jadwalPeriksa.dokter.poli')
                ->get()
                ->pluck('jadwalPeriksa.dokter.poli.nama_poli')
                ->unique()->values()->filter();

            // ─── ANTRIAN AKTIF PASIEN ─────────────────────────────────────
            $activeQueue = DaftarPoli::where('id_pasien', $pasien->id)
                ->whereNotNull('no_antrian')
                ->doesntHave('periksa')
                ->with(['jadwalPeriksa.dokter.poli'])
                ->latest()
                ->first();

            $noDilayani = 0;
            if ($activeQueue) {
                $noDilayani = DaftarPoli::where('id_jadwal', $activeQueue->id_jadwal)
                    ->whereHas('periksa')
                    ->max('no_antrian') ?? 0;
            }

            // ─── TABEL SEMUA JADWAL POLIKLINIK ───────────────────────────
            $jadwalAll = JadwalPeriksa::where('status', 'aktif')
                ->with(['dokter.poli'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get()
                ->map(function ($jadwal) {
                    $jadwal->no_dilayani = DaftarPoli::where('id_jadwal', $jadwal->id)
                        ->whereHas('periksa')
                        ->max('no_antrian') ?? 0;
                    return $jadwal;
                });

            $recentExams = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::where('id_pasien', $pasien->id)->pluck('id')
            )
            ->with(['daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksa.obat'])
            ->orderByDesc('tgl_periksa')
            ->limit(3)
            ->get();

            return view('pasien.dashboard', compact(
                'totalExaminations', 'lastExamination', 'doctorSpecialists',
                'activeQueue', 'noDilayani', 'jadwalAll', 'recentExams'
            ));
        } catch (\Exception $e) {
            return view('pasien.dashboard', [
                'totalExaminations' => 0, 'lastExamination' => null,
                'doctorSpecialists' => collect([]), 'activeQueue' => null,
                'noDilayani' => null, 'jadwalAll' => collect([]),
                'recentExams' => collect([]),
            ]);
        }
    }
}
