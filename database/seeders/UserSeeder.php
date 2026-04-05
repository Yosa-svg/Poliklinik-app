<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin123',
                'role' => 'admin',
                'no_ktp' => '1234567890123456',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Admin No. 1',
            ],
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'dokter@gmail.com',
                'password' => 'dokter123',
                'role' => 'dokter',
                'id_poli' => 1, // Pastikan poli dengan id 1 sudah ada
                'no_ktp' => '1234567890123457',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Dokter No. 1',
            ],
            [
                'name' => 'Pasien Test',
                'email' => 'pasien@gmail.com',
                'password' => 'pasien123',
                'role' => 'pasien',
                'no_ktp' => '1234567890123458',
                'no_hp' => '081234567892',
                'no_rm' => '202603-001',
                'alamat' => 'Jl. Pasien No. 1',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
