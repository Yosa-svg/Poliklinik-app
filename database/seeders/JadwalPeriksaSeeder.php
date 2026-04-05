<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalPeriksa;
use App\Models\User;

class JadwalPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get doctor user
        $dokter = User::where('role', 'dokter')->first();

        if ($dokter) {
            $schedules = [
                [
                    'id_dokter' => $dokter->id,
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '12:00',
                ],
                [
                    'id_dokter' => $dokter->id,
                    'hari' => 'Selasa',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '12:00',
                ],
                [
                    'id_dokter' => $dokter->id,
                    'hari' => 'Rabu',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '12:00',
                ],
                [
                    'id_dokter' => $dokter->id,
                    'hari' => 'Kamis',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '12:00',
                ],
                [
                    'id_dokter' => $dokter->id,
                    'hari' => 'Jumat',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '12:00',
                ],
            ];

            foreach ($schedules as $schedule) {
                JadwalPeriksa::updateOrCreate(
                    ['id_dokter' => $schedule['id_dokter'], 'hari' => $schedule['hari']],
                    $schedule
                );
            }
        }
    }
}
