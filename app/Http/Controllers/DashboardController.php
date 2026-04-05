<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use App\Models\DaftarPoli;
use App\Models\Periksa;

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
            
            // Patients registered last 30 days
            $lastMonthPatients = User::where('role', 'pasien')
                ->where('created_at', '>=', now()->subDays(30))
                ->count();
            
            // Examinations by clinic
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
                ->map(function ($poli) {
                    return [
                        'name' => $poli->nama_poli,
                        'count' => $poli->count
                    ];
                });
            
            // Recent activity (latest registrations)
            $recentRegistrations = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
            
            // Recent examinations
            $recentExaminations = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalPoli',
                'totalDokter',
                'totalPasien',
                'totalPemeriksaan',
                'lastMonthPatients',
                'pemeriksaanByClinic',
                'recentRegistrations',
                'recentExaminations'
            ));
        } catch (\Exception $e) {
            return view('admin.dashboard', [
                'totalPoli' => 0,
                'totalDokter' => 0,
                'totalPasien' => 0,
                'totalPemeriksaan' => 0,
                'lastMonthPatients' => 0,
                'pemeriksaanByClinic' => collect([]),
                'recentRegistrations' => collect([]),
                'recentExaminations' => collect([])
            ]);
        }
    }

    // ─── Doctor Dashboard ────────────────────────────────────────────────────
    public function dokterDashboard()
    {
        try {
            $dokter = auth()->user();
            
            // Get doctor's jadwal IDs
            $jadwalIds = $dokter->jadwalPeriksa()->pluck('id')->toArray();
            
            // My examined patients this month
            $periksasThisMonth = Periksa::whereYear('tgl_periksa', date('Y'))
                ->whereMonth('tgl_periksa', date('m'))
                ->whereIn('id_daftar_poli', DaftarPoli::whereIn('id_jadwal', $jadwalIds)->pluck('id'))
                ->count();
            
            // Pending examinations
            $pendingExaminations = DaftarPoli::whereIn('id_jadwal', $jadwalIds)
                ->doesntHave('periksa')
                ->count();
            
            // All examinations
            $totalExaminations = Periksa::whereIn('id_daftar_poli', 
                DaftarPoli::whereIn('id_jadwal', $jadwalIds)->pluck('id')
            )->count();
            
            // Revenue this month
            $revenueThisMonth = Periksa::whereYear('tgl_periksa', date('Y'))
                ->whereMonth('tgl_periksa', date('m'))
                ->whereIn('id_daftar_poli', DaftarPoli::whereIn('id_jadwal', $jadwalIds)->pluck('id'))
                ->sum('biaya_periksa') ?? 0;
            
            // Most common complaints
            $commonComplaints = DaftarPoli::whereIn('id_jadwal', $jadwalIds)
                ->whereNotNull('keluhan')
                ->selectRaw('keluhan, COUNT(*) as count')
                ->groupBy('keluhan')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->pluck('count', 'keluhan');

            return view('dokter.dashboard', compact(
                'periksasThisMonth',
                'pendingExaminations',
                'totalExaminations',
                'revenueThisMonth',
                'commonComplaints'
            ));
        } catch (\Exception $e) {
            return view('dokter.dashboard', [
                'periksasThisMonth' => 0,
                'pendingExaminations' => 0,
                'totalExaminations' => 0,
                'revenueThisMonth' => 0,
                'commonComplaints' => collect([])
            ]);
        }
    }

    // ─── Patient Dashboard ───────────────────────────────────────────────────
    public function pasienDashboard()
    {

        try {
            $pasien = auth()->user();
            
            // Total examinations
            $totalExaminations = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::where('id_pasien', $pasien->id)->pluck('id')
            )->count();
            
            // Last examination date
            $lastExamination = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::where('id_pasien', $pasien->id)->pluck('id')
            )
            ->orderByDesc('tgl_periksa')
            ->first();
            
            // Doctor specialists visited
            $doctorSpecialists = DaftarPoli::where('id_pasien', $pasien->id)
                ->with('jadwalPeriksa.dokter.poli')
                ->get()
                ->pluck('jadwalPeriksa.dokter.poli.nama_poli')
                ->unique()
                ->values()
                ->filter();
            
            // Next upcoming appointment (if any)
            $nextAppointment = DaftarPoli::where('id_pasien', $pasien->id)
                ->whereNotNull('no_antrian')
                ->with(['jadwalPeriksa.dokter.poli', 'periksa'])
                ->get()
                ->filter(function ($daftar) {
                    return !$daftar->periksa; // Not yet examined
                })
                ->first();
            
            // Recent exams
            $recentExams = Periksa::whereIn('id_daftar_poli',
                DaftarPoli::where('id_pasien', $pasien->id)->pluck('id')
            )
            ->with(['daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksa.obat'])
            ->orderByDesc('tgl_periksa')
            ->limit(3)
            ->get();

            return view('pasien.dashboard', compact(
                'totalExaminations',
                'lastExamination',
                'doctorSpecialists',
                'nextAppointment',
                'recentExams'
            ));
        } catch (\Exception $e) {
            return view('pasien.dashboard', [
                'totalExaminations' => 0,
                'lastExamination' => null,
                'doctorSpecialists' => collect([]),
                'nextAppointment' => null,
                'recentExams' => collect([])
            ]);
        }
    }
}
