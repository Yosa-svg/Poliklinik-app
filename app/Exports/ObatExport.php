<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Obat;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ObatExport
{
    public function download(): StreamedResponse
    {
        $obats = Obat::orderBy('nama_obat')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Obat');

        $headers = ['No', 'Nama Obat', 'Kemasan', 'Harga (Rp)', 'Stok', 'Status Stok'];
        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}1", $header);
            $sheet->getStyle("{$col}1")->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach ($obats as $i => $obat) {
            $row = $i + 2;
            $stokStatus = $obat->stok <= 0 ? 'Habis' : ($obat->stok <= 5 ? 'Menipis' : 'Tersedia');
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $obat->nama_obat);
            $sheet->setCellValue("C{$row}", $obat->kemasan);
            $sheet->setCellValue("D{$row}", $obat->harga);
            $sheet->setCellValue("E{$row}", $obat->stok ?? 0);
            $sheet->setCellValue("F{$row}", $stokStatus);
        }

        $filename = 'data-obat-' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
