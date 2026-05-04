<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DokterExport
{
    public function download(): StreamedResponse
    {
        $dokters = User::where('role', 'dokter')->with('poli')->orderBy('name')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Dokter');

        // Headers
        $headers = ['No', 'Nama Dokter', 'Email', 'Alamat', 'No. Telepon', 'Poliklinik', 'Terdaftar Sejak'];
        foreach ($headers as $i => $header) {
            $col = chr(65 + $i); // A, B, C...
            $sheet->setCellValue("{$col}1", $header);
            $sheet->getStyle("{$col}1")->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Data rows
        foreach ($dokters as $i => $dokter) {
            $row = $i + 2;
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $dokter->name);
            $sheet->setCellValue("C{$row}", $dokter->email);
            $sheet->setCellValue("D{$row}", $dokter->alamat ?? '-');
            $sheet->setCellValue("E{$row}", $dokter->no_hp ?? '-');
            $sheet->setCellValue("F{$row}", $dokter->poli->nama_poli ?? '-');
            $sheet->setCellValue("G{$row}", $dokter->created_at->format('d/m/Y'));
        }

        $filename = 'data-dokter-' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
