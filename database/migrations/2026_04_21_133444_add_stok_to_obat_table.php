<?php

/**
 * ============================================================
 * Migration: add_stok_to_obat_table
 * ============================================================
 * TUJUAN     : Menambahkan kolom 'stok' ke tabel 'obat'
 *              yang sudah ada sebelumnya.
 *
 * MENGAPA MIGRASI TERPISAH?
 *   Kolom 'stok' ditambahkan belakangan (setelah tabel
 *   awal dibuat) karena merupakan fitur manajemen stok
 *   yang dikembangkan di fase berikutnya. Dalam Laravel,
 *   perubahan skema dilakukan dengan menambah file migrasi
 *   baru — BUKAN mengedit migrasi yang sudah ada.
 *   Ini menjaga histori perubahan database tetap terlacak.
 *
 * DIJALANKAN : php artisan migrate
 * DIBATALKAN : php artisan migrate:rollback
 * ============================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom 'stok' ke tabel 'obat' yang sudah ada.
     * Dipanggil saat: php artisan migrate
     */
    public function up(): void
    {
        // Schema::table() digunakan untuk MENGUBAH tabel yang sudah ada
        // (berbeda dengan Schema::create() yang membuat tabel baru)
        Schema::table('obat', function (Blueprint $table) {

            // Tambahkan kolom 'stok':
            //   - Tipe: integer (bilangan bulat, cocok untuk jumlah stok)
            //   - default(0): jika tidak diisi, nilainya 0 (bukan NULL)
            //   - after('harga'): letakkan kolom ini SETELAH kolom 'harga'
            //     (hanya berpengaruh di MySQL, tidak di SQLite)
            $table->integer('stok')->default(0)->after('harga');
        });
    }

    /**
     * Batalkan penambahan kolom 'stok' (rollback).
     * Dipanggil saat: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            // Hapus kolom 'stok' dari tabel (kembalikan ke kondisi sebelumnya)
            $table->dropColumn('stok');
        });
    }
};
