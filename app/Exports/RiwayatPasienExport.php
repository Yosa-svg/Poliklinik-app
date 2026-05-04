<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Periksa;
use App\Models\DaftarPoli;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RiwayatPasienExport
{
    protected int $dokterId;

    public function __construct(int $dokterId)
    {
        $this->dokterId = $dokterId;
    }

    public function download(): StreamedResponse
    {
        $periksas = Periksa::whereIn(
            'id_daftar_poli',
            DaftarPoli::whereHas('jadwalPeriksa', fn($q) => $q->where('id_dokter', $this->dokterId))->pluck('id')
        )
        ->with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksa.obat',
        ])
        ->orderByDesc('tgl_periksa')
        ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Pasien');

        $headers = ['No', 'No. RM', 'Nama Pasien', 'Poliklinik', 'Tgl Periksa', 'Catatan', 'Obat', 'Biaya (Rp)', 'Status Bayar'];
        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}1", $header);
            $sheet->getStyle("{$col}1")->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach ($periksas as $i => $periksa) {
            $row = $i + 2;
            $obatList = $periksa->detailPeriksa->pluck('obat.nama_obat')->filter()->implode(', ');
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $periksa->daftarPoli->pasien->no_rm ?? '-');
            $sheet->setCellValue("C{$row}", $periksa->daftarPoli->pasien->name ?? '-');
            $sheet->setCellValue("D{$row}", $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-');
            $sheet->setCellValue("E{$row}", $periksa->tgl_periksa->format('d/m/Y'));
            $sheet->setCellValue("F{$row}", $periksa->catatan);
            $sheet->setCellValue("G{$row}", $obatList ?: '-');
            $sheet->setCellValue("H{$row}", $periksa->biaya_periksa);
            $sheet->setCellValue("I{$row}", $periksa->status_bayar_label ?? '-');
        }

        $filename = 'riwayat-pasien-' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
