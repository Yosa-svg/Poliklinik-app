<?php

/**
 * ============================================================
 * Obat.php  —  Model
 * ============================================================
 * PERAN (dalam MVC) : Model
 * TANGGUNG JAWAB    : Merepresentasikan tabel 'obat' di database.
 *                     Model adalah penghubung antara kode PHP dan
 *                     tabel database melalui Eloquent ORM Laravel.
 *
 * TABEL DATABASE    : obat
 * KOLOM             : id, nama_obat, kemasan, harga, stok,
 *                     created_at, updated_at
 *
 * RELASI            :
 *   Obat hasMany DetailPeriksa
 *   → Satu obat bisa digunakan di banyak detail pemeriksaan.
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    /**
     * Nama tabel di database yang diwakili model ini.
     * Jika tidak dideklarasikan, Laravel otomatis menebak
     * nama tabel dari nama kelas (lowercase + plural) → 'obats'.
     * Karena tabel kita bernama 'obat' (bukan 'obats'), perlu
     * dinyatakan secara eksplisit.
     */
    protected $table = 'obat';

    /**
     * Daftar kolom yang BOLEH diisi secara massal (Mass Assignment).
     *
     * Mass Assignment adalah teknik mengisi banyak kolom sekaligus
     * menggunakan array, contoh: Obat::create([...]) atau $obat->update([...]).
     *
     * ⚠️  Kolom yang TIDAK ada di $fillable tidak akan bisa diisi
     *     dengan cara ini — ini adalah fitur keamanan Laravel untuk
     *     mencegah pengguna menyisipkan kolom berbahaya (misal: is_admin).
     *
     * Kolom 'id', 'created_at', 'updated_at' tidak perlu didaftarkan
     * karena dikelola otomatis oleh Laravel/database.
     */
    protected $fillable = [
        'nama_obat', // Nama obat, contoh: "Paracetamol 500mg"
        'kemasan',   // Jenis kemasan, contoh: "Strip", "Botol", "Tablet"
        'harga',     // Harga satuan dalam rupiah (disimpan sebagai integer)
        'stok',      // Jumlah stok yang tersedia
    ];

    // ─── HELPER METHODS (Logika Bisnis) ──────────────────────────

    /**
     * Cek apakah stok obat dalam kondisi MENIPIS.
     *
     * Threshold: stok ≤ 5 dianggap menipis.
     * Digunakan di View (index.blade.php) untuk menampilkan
     * badge berwarna kuning sebagai peringatan.
     *
     * Contoh penggunaan di Blade:
     *   @if($obat->isLowStock()) ... @endif
     *
     * @return bool  true jika stok ≤ 5, false jika tidak
     */
    public function isLowStock(): bool
    {
        return $this->stok <= 5;
    }

    /**
     * Cek apakah stok obat sudah HABIS.
     *
     * Threshold: stok ≤ 0 dianggap habis.
     * Digunakan di View untuk menampilkan badge merah
     * dan memblokir penulisan resep obat tersebut.
     *
     * @return bool  true jika stok ≤ 0, false jika tidak
     */
    public function isOutOfStock(): bool
    {
        return $this->stok <= 0;
    }

    // ─── RELASI ELOQUENT ─────────────────────────────────────────

    /**
     * Relasi: Satu Obat → Banyak DetailPeriksa  (hasMany)
     *
     * Artinya: satu jenis obat bisa muncul di banyak
     * detail pemeriksaan (diresepkan ke banyak pasien).
     *
     * Foreign key di tabel 'detail_periksa': id_obat
     *
     * Contoh penggunaan:
     *   $obat->detailPeriksa          → koleksi semua detail periksa
     *   $obat->detailPeriksa()->count() → hitung berapa kali diresepkan
     *
     * Diagram:
     *   obat (1) ──────────< detail_periksa (many)
     *         id               id_obat (FK)
     */
    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
        //                    ↑ Model tujuan           ↑ Foreign key di tabel detail_periksa
    }
}
