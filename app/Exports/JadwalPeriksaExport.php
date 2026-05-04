<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\JadwalPeriksa;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JadwalPeriksaExport
{
    protected int $dokterId;

    public function __construct(int $dokterId)
    {
        $this->dokterId = $dokterId;
    }

    public function download(): StreamedResponse
    {
        $jadwals = JadwalPeriksa::where('id_dokter', $this->dokterId)
            ->with(['dokter.poli', 'daftarPoli'])
            ->orderBy('hari')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jadwal Periksa');

        $headers = ['No', 'Poliklinik', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Status', 'Jumlah Pasien'];
        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}1", $header);
            $sheet->getStyle("{$col}1")->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach ($jadwals as $i => $jadwal) {
            $row = $i + 2;
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $jadwal->dokter->poli->nama_poli ?? '-');
            $sheet->setCellValue("C{$row}", $jadwal->hari);
            $sheet->setCellValue("D{$row}", $jadwal->jam_mulai);
            $sheet->setCellValue("E{$row}", $jadwal->jam_selesai);
            $sheet->setCellValue("F{$row}", $jadwal->status === 'aktif' ? 'Aktif' : 'Tidak Aktif');
            $sheet->setCellValue("G{$row}", $jadwal->daftarPoli->count());
        }

        $filename = 'jadwal-periksa-' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
