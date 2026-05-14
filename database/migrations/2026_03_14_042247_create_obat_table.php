<?php

/**
 * ============================================================
 * Migration: create_obat_table
 * ============================================================
 * TUJUAN     : Membuat tabel 'obat' di database.
 * DIJALANKAN : php artisan migrate
 * DIBATALKAN : php artisan migrate:rollback
 *
 * Tabel ini menyimpan data master obat yang tersedia
 * di klinik. Data di sini digunakan saat dokter meresepkan
 * obat kepada pasien (melalui tabel detail_periksa).
 * ============================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // Helper untuk mendefinisikan kolom tabel
use Illuminate\Support\Facades\Schema;   // Facade untuk operasi DDL (CREATE, DROP, ALTER)

return new class extends Migration
{
    /**
     * Jalankan migrasi — membuat tabel baru.
     * Dipanggil saat: php artisan migrate
     */
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $table) {

            // Kolom 'id' → Primary Key, auto-increment (integer)
            // Setara SQL: id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->id();

            // Nama obat, contoh: "Paracetamol 500mg", "Amoxicillin"
            // Maksimal 60 karakter, NOT NULL (wajib diisi)
            $table->string('nama_obat', 60);

            // Jenis kemasan, contoh: "Strip", "Botol", "Tablet", "Kapsul"
            // nullable() → boleh kosong / NULL di database
            $table->string('kemasan', 35)->nullable();

            // Harga obat per satuan dalam rupiah (tanpa desimal)
            // Tipe integer cocok untuk mata uang rupiah yang tidak perlu desimal
            $table->integer('harga');

            // Kolom created_at dan updated_at (dikelola otomatis Eloquent)
            // created_at → waktu record pertama dibuat
            // updated_at → waktu record terakhir diubah
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi — hapus tabel.
     * Dipanggil saat: php artisan migrate:rollback
     *
     * dropIfExists() lebih aman dari drop() karena tidak error
     * jika tabel sudah tidak ada.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
