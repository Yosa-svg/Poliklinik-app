<?php

/**
 * ============================================================
 * DetailPeriksa.php  —  Model (Tabel Pivot / Junction)
 * ============================================================
 * PERAN (dalam MVC) : Model
 * TANGGUNG JAWAB    : Merepresentasikan tabel 'detail_periksa'
 *                     yang berfungsi sebagai tabel penghubung
 *                     antara tabel 'periksa' dan tabel 'obat'.
 *
 * TABEL DATABASE    : detail_periksa
 * KOLOM             : id, id_periksa (FK), id_obat (FK),
 *                     created_at, updated_at
 *
 * RELASI            :
 *   DetailPeriksa belongsTo Periksa  → setiap detail milik satu periksa
 *   DetailPeriksa belongsTo Obat     → setiap detail merujuk satu obat
 *
 * GAMBARAN ERD:
 *   periksa (1) ──< detail_periksa >── (1) obat
 *   → Satu pemeriksaan bisa memiliki banyak obat (via detail_periksa)
 *   → Satu obat bisa digunakan di banyak pemeriksaan
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeriksa extends Model
{
    /**
     * Nama tabel di database.
     * Laravel tidak bisa otomatis menebak 'detail_periksa'
     * dari nama kelas 'DetailPeriksa', jadi dideklarasikan eksplisit.
     */
    protected $table = 'detail_periksa';

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment protection).
     *
     * 'id_periksa' → foreign key ke tabel 'periksa' (sesi pemeriksaan)
     * 'id_obat'    → foreign key ke tabel 'obat' (obat yang diresepkan)
     *
     * Satu baris detail_periksa = satu obat yang diresepkan
     * dalam satu sesi pemeriksaan.
     */
    protected $fillable = [
        'id_periksa', // Merujuk ke pemeriksaan mana obat ini diresepkan
        'id_obat',    // Merujuk ke obat mana yang diresepkan
    ];

    // ─── RELASI ELOQUENT ─────────────────────────────────────────

    /**
     * Relasi: DetailPeriksa → Periksa  (belongsTo / Many-to-One)
     *
     * Setiap baris detail_periksa MILIK SATU pemeriksaan (periksa).
     * Foreign key yang digunakan adalah kolom 'id_periksa'.
     *
     * Contoh penggunaan:
     *   $detail->periksa           → objek Periksa yang berelasi
     *   $detail->periksa->catatan  → akses catatan pemeriksaan
     */
    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
        //                      ↑ Model tujuan    ↑ Foreign key di tabel ini
    }

    /**
     * Relasi: DetailPeriksa → Obat  (belongsTo / Many-to-One)
     *
     * Setiap baris detail_periksa MERUJUK KE SATU obat.
     * Foreign key yang digunakan adalah kolom 'id_obat'.
     *
     * Contoh penggunaan:
     *   $detail->obat        → objek Obat yang berelasi
     *   $detail->obat->harga → akses harga obat tersebut
     *
     * Digunakan di Periksa::getTotalBiayaAttribute() untuk
     * menjumlahkan total harga semua obat yang diresepkan.
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
        //                      ↑ Model tujuan  ↑ Foreign key di tabel ini
    }
}
