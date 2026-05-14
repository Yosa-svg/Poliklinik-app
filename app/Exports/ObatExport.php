<?php

/**
 * ============================================================
 * ObatExport.php  —  Export Class
 * ============================================================
 * PERAN            : Kelas khusus untuk mengekspor data obat
 *                    ke format file Excel (.xlsx).
 * LOKASI           : app/Exports/ObatExport.php
 *
 * LIBRARY          : PhpSpreadsheet (PhpOffice\PhpSpreadsheet)
 *                    Library PHP murni untuk membuat/membaca
 *                    file spreadsheet (Excel, CSV, dll.)
 *
 * DIPANGGIL DARI   : ObatController::export()
 * ROUTE            : GET /obat/export/excel  (obat.export)
 *
 * ISI FILE EXCEL   :
 *   Kolom A: No (nomor urut)
 *   Kolom B: Nama Obat
 *   Kolom C: Kemasan
 *   Kolom D: Harga (Rp)
 *   Kolom E: Stok
 *   Kolom F: Status Stok (Tersedia / Menipis / Habis)
 * ============================================================
 */

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;          // Kelas utama untuk membuat dokumen spreadsheet
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;          // Writer khusus format .xlsx (Excel 2007+)
use App\Models\Obat;                               // Model untuk ambil data dari tabel 'obat'
use Symfony\Component\HttpFoundation\StreamedResponse; // Tipe return untuk streaming file ke browser

class ObatExport
{
    /**
     * Generate file Excel dan kirimkan ke browser sebagai unduhan.
     *
     * ALUR KERJA:
     *   1. Ambil semua data obat dari database (urut A-Z).
     *   2. Buat objek Spreadsheet baru (dokumen Excel kosong).
     *   3. Ambil sheet aktif (tab pertama) dan beri nama.
     *   4. Tulis baris header (baris ke-1) dengan tulisan tebal.
     *   5. Loop setiap obat dan tulis ke baris berikutnya.
     *   6. Tentukan nama file dengan tanggal hari ini.
     *   7. Stream file ke browser → browser otomatis unduh.
     *
     * @return StreamedResponse  Response streaming file Excel
     */
    public function download(): StreamedResponse
    {
        // ── AMBIL DATA DARI DATABASE ────────────────────────────
        // Query: SELECT * FROM obat ORDER BY nama_obat ASC
        $obats = Obat::orderBy('nama_obat')->get();

        // ── BUAT DOKUMEN SPREADSHEET BARU ───────────────────────
        $spreadsheet = new Spreadsheet();

        // Ambil sheet (tab) pertama yang sudah ada secara default
        $sheet = $spreadsheet->getActiveSheet();

        // Beri nama pada tab sheet
        $sheet->setTitle('Data Obat');

        // ── TULIS BARIS HEADER (BARIS KE-1) ─────────────────────
        // Header ini adalah judul kolom di baris pertama Excel
        $headers = ['No', 'Nama Obat', 'Kemasan', 'Harga (Rp)', 'Stok', 'Status Stok'];

        foreach ($headers as $i => $header) {
            // chr(65) = 'A', chr(66) = 'B', dst.
            // Jadi kolom ke-0 = A, ke-1 = B, ke-2 = C, ...
            $col = chr(65 + $i);

            // Tulis teks header ke sel (misal: A1, B1, C1, ...)
            $sheet->setCellValue("{$col}1", $header);

            // Tebalkan teks header agar mudah dibedakan dari data
            $sheet->getStyle("{$col}1")->getFont()->setBold(true);

            // Atur lebar kolom secara otomatis sesuai isi konten
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ── TULIS DATA OBAT (MULAI DARI BARIS KE-2) ─────────────
        foreach ($obats as $i => $obat) {
            $row = $i + 2; // Baris 1 sudah dipakai header, data mulai baris 2

            // Tentukan status stok berdasarkan nilai kolom stok
            // Logika: stok=0 → Habis | stok≤5 → Menipis | stok>5 → Tersedia
            $stokStatus = $obat->stok <= 0 ? 'Habis' : ($obat->stok <= 5 ? 'Menipis' : 'Tersedia');

            // Tulis setiap kolom ke sel yang sesuai
            $sheet->setCellValue("A{$row}", $i + 1);           // No (nomor urut, mulai dari 1)
            $sheet->setCellValue("B{$row}", $obat->nama_obat); // Nama Obat
            $sheet->setCellValue("C{$row}", $obat->kemasan);   // Kemasan
            $sheet->setCellValue("D{$row}", $obat->harga);     // Harga (angka mentah, tanpa format Rp)
            $sheet->setCellValue("E{$row}", $obat->stok ?? 0); // Stok (?? 0 = fallback jika null)
            $sheet->setCellValue("F{$row}", $stokStatus);      // Status stok (teks)
        }

        // ── SIAPKAN NAMA FILE DAN WRITER ─────────────────────────
        // Nama file menyertakan tanggal hari ini, contoh: data-obat-20260513.xlsx
        $filename = 'data-obat-' . date('Ymd') . '.xlsx';

        // Buat writer format .xlsx (format Excel modern)
        $writer = new Xlsx($spreadsheet);

        // ── STREAM FILE KE BROWSER ───────────────────────────────
        // streamDownload() mengirim file langsung ke browser tanpa
        // menyimpan file sementara ke server.
        // Parameter: callback tulis file | nama file | header HTTP
        return response()->streamDownload(function () use ($writer) {
            // php://output = tulis langsung ke output buffer HTTP
            $writer->save('php://output');
        }, $filename, [
            // Content-Type memberi tahu browser bahwa ini file Excel
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
