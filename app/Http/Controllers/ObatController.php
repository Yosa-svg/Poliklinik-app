<?php

/**
 * ============================================================
 * ObatController.php
 * ============================================================
 * PERAN (dalam MVC) : Controller
 * TANGGUNG JAWAB    : Mengelola seluruh operasi CRUD data Obat.
 *                     Controller menjadi jembatan antara Model (Obat)
 *                     dan View (resources/views/admin/obat/).
 *
 * ROUTE yang dilayani (dari Route::resource('obat', ...)):
 *   GET    /obat              → index()   — tampilkan daftar obat
 *   GET    /obat/create       → create()  — tampilkan form tambah
 *   POST   /obat              → store()   — simpan data baru
 *   GET    /obat/{id}/edit    → edit()    — tampilkan form edit
 *   PUT    /obat/{id}         → update()  — simpan perubahan
 *   DELETE /obat/{id}         → destroy() — hapus data
 *   GET    /obat/export/excel → export()  — unduh file Excel
 *
 * AKSES : hanya role 'admin' (middleware 'role:admin' di web.php)
 * ============================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Kelas untuk menangkap data dari request HTTP (form, query string, dll)
use App\Models\Obat;         // Model yang merepresentasikan tabel 'obat' di database
use App\Exports\ObatExport;  // Kelas khusus untuk ekspor data ke file Excel

class ObatController extends Controller
{
    /**
     * ─── READ (INDEX) ────────────────────────────────────────────
     * Menampilkan seluruh daftar obat ke halaman admin.
     *
     * ALUR:
     *   1. Ambil semua data obat dari tabel 'obat',
     *      diurutkan A-Z berdasarkan nama_obat.
     *   2. Kirim data ke view 'admin.obat.index'.
     *
     * QUERY yang dijalankan:
     *   SELECT * FROM obat ORDER BY nama_obat ASC
     */
    public function index()
    {
        // Ambil semua obat, urut berdasarkan nama agar mudah dicari
        $obats = Obat::orderBy('nama_obat')->get();

        // Kirim variabel $obats ke view menggunakan helper with()
        return view('admin.obat.index')->with([
            'obats' => $obats,
        ]);
    }

    /**
     * ─── CREATE (TAMPILKAN FORM) ─────────────────────────────────
     * Menampilkan halaman form untuk menambah obat baru.
     *
     * ALUR:
     *   1. Tidak perlu query database (form kosong).
     *   2. Kembalikan view form create.
     *
     * Catatan: data dikirim via POST ke method store() setelah
     *          pengguna mengklik tombol Simpan.
     */
    public function create()
    {
        // Langsung kembalikan form kosong (tidak butuh data dari DB)
       return view('admin.obat.create');
    }

    /**
     * ─── CREATE (SIMPAN DATA) ────────────────────────────────────
     * Menerima dan memvalidasi input dari form, lalu menyimpan
     * data obat baru ke dalam database.
     *
     * ALUR:
     *   1. Validasi semua field yang dikirim dari form.
     *      → Jika gagal: Laravel otomatis redirect balik ke form
     *        dengan pesan error dan data lama (old input).
     *   2. Buat record baru di tabel 'obat' via Obat::create().
     *   3. Redirect ke halaman index dengan flash message sukses.
     *
     * QUERY yang dijalankan:
     *   INSERT INTO obat (nama_obat, kemasan, harga, stok, created_at, updated_at)
     *   VALUES (?, ?, ?, ?, NOW(), NOW())
     *
     * @param  Request $request  Data form yang dikirim pengguna (POST)
     */
    public function store(Request $request)
    {
        // ── VALIDASI INPUT ──────────────────────────────────────
        // Jika ada field yang tidak memenuhi aturan, Laravel akan
        // otomatis mengembalikan pengguna ke form dengan error message.
        $request->validate([
            'nama_obat' => 'required|string|max:255', // Wajib diisi, teks, maksimal 255 karakter
            'kemasan'   => 'required|string|max:255', // Wajib diisi, teks (contoh: Strip, Tablet, Botol)
            'harga'     => 'required|numeric|min:0',  // Wajib angka, tidak boleh negatif
            'stok'      => 'required|integer|min:0',  // Wajib bilangan bulat, tidak boleh negatif
        ]);

        // ── INSERT KE DATABASE ──────────────────────────────────
        // Obat::create() aman dari Mass Assignment karena kolom
        // ini sudah didaftarkan di $fillable pada model Obat.
        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
        ]);

        // ── REDIRECT DENGAN FLASH MESSAGE ───────────────────────
        // Kirim flash session 'status' → digunakan view untuk
        // menampilkan notifikasi "Obat berhasil ditambahkan".
        return redirect()->route('obat.index')->with('status', 'obat-created');
    }

    /**
     * ─── SHOW (tidak digunakan) ──────────────────────────────────
     * Biasanya untuk menampilkan detail satu record.
     * Pada fitur obat ini, detail tidak diperlukan (cukup list).
     *
     * @param  string $id
     */
    public function show(string $id)
    {
        // Tidak diimplementasikan — admin cukup melihat tabel index
    }

    /**
     * ─── UPDATE (TAMPILKAN FORM EDIT) ────────────────────────────
     * Menampilkan form edit yang sudah terisi dengan data obat lama.
     *
     * ALUR:
     *   1. Laravel secara otomatis mencari record Obat berdasarkan
     *      ID yang ada di URL → inilah "Route Model Binding".
     *      Contoh: GET /obat/5/edit → Laravel cari Obat::find(5)
     *      Jika tidak ditemukan → otomatis error 404.
     *   2. Kirim objek $obat ke view edit.
     *
     * @param  Obat $obat  Objek obat yang otomatis di-inject Laravel
     */
    public function edit(Obat $obat)
    {
        // compact('obat') = shortcut untuk ['obat' => $obat]
        // Data obat lama langsung tersedia di view untuk mengisi field value=""
        return view('admin.obat.edit', compact('obat'));
    }

    /**
     * ─── UPDATE (SIMPAN PERUBAHAN) ───────────────────────────────
     * Memvalidasi input dari form edit, lalu memperbarui record
     * di database dengan data yang baru.
     *
     * ALUR:
     *   1. Validasi field (sama seperti store).
     *   2. Update record menggunakan $obat->update() — Eloquent
     *      otomatis tahu record mana yang perlu diperbarui (by ID).
     *   3. Redirect ke index dengan flash message sukses.
     *
     * QUERY yang dijalankan:
     *   UPDATE obat SET nama_obat=?, kemasan=?, harga=?, stok=?,
     *   updated_at=NOW() WHERE id=?
     *
     * Catatan: HTTP form HTML tidak mendukung method PUT.
     *   View menggunakan @method('PUT') → Laravel membaca
     *   hidden field _method=PUT untuk method spoofing.
     *
     * @param  Request $request  Data form yang diubah pengguna
     * @param  Obat    $obat     Record obat yang akan diupdate (Route Model Binding)
     */
    public function update(Request $request, Obat $obat)
    {
        // ── VALIDASI INPUT ──────────────────────────────────────
        // Aturan validasi sama dengan store() karena field-nya identik
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan'   => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
        ]);

        // ── UPDATE RECORD DI DATABASE ───────────────────────────
        // $obat sudah berisi data lama dari DB (via Route Model Binding).
        // Method update() menimpa kolom yang disebutkan saja.
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
        ]);

        // ── REDIRECT DENGAN FLASH MESSAGE ───────────────────────
        // 'message' = teks notifikasi, 'type' = jenis alert (success/danger)
        return redirect()->route('obat.index')
            ->with('message', 'Obat berhasil diupdate.')
            ->with('type', 'success');
    }

    /**
     * ─── DELETE ──────────────────────────────────────────────────
     * Menghapus record obat dari database.
     *
     * ALUR:
     *   1. Laravel inject objek $obat via Route Model Binding.
     *   2. Panggil $obat->delete() → Eloquent jalankan query DELETE.
     *   3. Redirect ke index dengan notifikasi sukses.
     *
     * QUERY yang dijalankan:
     *   DELETE FROM obat WHERE id=?
     *
     * ⚠️  PERHATIAN: Karena migrasi detail_periksa menggunakan
     *     cascadeOnDelete(), semua record di tabel 'detail_periksa'
     *     yang berelasi dengan obat ini juga akan ikut terhapus!
     *
     * @param  Obat $obat  Record obat yang akan dihapus (Route Model Binding)
     */
    public function destroy(Obat $obat)
    {
        // Hapus record dari database
        // → otomatis cascade hapus detail_periksa yang berelasi
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Obat berhasil dihapus.')
            ->with('type', 'success');
    }

    /**
     * ─── EXPORT EXCEL ────────────────────────────────────────────
     * Mengunduh seluruh data obat dalam format file Excel (.xlsx).
     *
     * ALUR:
     *   1. Buat instance ObatExport (kelas di app/Exports/).
     *   2. Panggil method download() yang menggunakan PhpSpreadsheet
     *      untuk generate file Excel dan stream ke browser.
     *
     * Route: GET /obat/export/excel → obat.export
     */
    public function export()
    {
        // Delegasikan proses pembuatan file ke kelas ObatExport
        return (new ObatExport)->download();
    }
}
