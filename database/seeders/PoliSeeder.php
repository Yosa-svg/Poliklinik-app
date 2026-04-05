<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Poli Umum',
            ],
            [
                'nama_poli' => 'Poli Gigi',
            ],
            [
                'nama_poli' => 'Poli Anak',
            ],
            [
                'nama_poli' => 'Poli Mata',
            ],
        ];

        foreach ($polis as $poli) {
            Poli::updateOrCreate(
                ['nama_poli' => $poli['nama_poli']],
                $poli
            );
        }
    }
}
