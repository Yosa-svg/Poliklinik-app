<?php

/**
 * ============================================================
 * Migration: create_detail_periksa_table
 * ============================================================
 * TUJUAN     : Membuat tabel 'detail_periksa' sebagai
 *              tabel JUNCTION (penghubung) antara tabel
 *              'periksa' dan tabel 'obat'.
 *
 * KONSEP     : Tabel ini mewakili relasi Many-to-Many antara
 *              pemeriksaan dan obat:
 *              - Satu pemeriksaan bisa memiliki banyak obat
 *              - Satu obat bisa diresepkan di banyak pemeriksaan
 *
 * FOREIGN KEY:
 *   id_periksa → merujuk ke tabel 'periksa' (sesi pemeriksaan)
 *   id_obat    → merujuk ke tabel 'obat' (obat yang diresepkan)
 *
 * CASCADE DELETE:
 *   Jika record 'periksa' dihapus → semua detail_periksa-nya ikut terhapus
 *   Jika record 'obat' dihapus   → semua detail_periksa-nya ikut terhapus
 * ============================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel 'detail_periksa'.
     * Tabel ini harus dibuat SETELAH tabel 'periksa' dan 'obat'
     * karena memiliki foreign key ke kedua tabel tersebut.
     */
    public function up(): void
    {
        Schema::create('detail_periksa', function (Blueprint $table) {

            // Primary key auto-increment
            $table->id();

            // ── FOREIGN KEY: id_periksa ─────────────────────────
            // foreignId() = kolom integer unsigned (cocok untuk FK)
            // constrained('periksa') = buat constraint FK ke tabel 'periksa'
            // cascadeOnDelete() = jika record di tabel 'periksa' dihapus,
            //   semua detail_periksa yang berelasi IKUT TERHAPUS OTOMATIS
            $table->foreignId('id_periksa')->constrained('periksa')->cascadeOnDelete();

            // ── FOREIGN KEY: id_obat ────────────────────────────
            // Sama seperti di atas, tetapi merujuk ke tabel 'obat'.
            // cascadeOnDelete() = jika obat dihapus dari master,
            //   semua detail pemeriksaan yang menggunakan obat itu
            //   juga ikut terhapus.
            $table->foreignId('id_obat')->constrained('obat')->cascadeOnDelete();

            // Timestamp created_at dan updated_at (dikelola Eloquent)
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel 'detail_periksa' saat rollback.
     * Tabel ini harus dihapus SEBELUM tabel 'periksa' dan 'obat'
     * karena memiliki dependency (foreign key) ke mereka.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_periksa');
    }
};
