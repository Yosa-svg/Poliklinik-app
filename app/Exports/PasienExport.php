<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PasienExport
{
    public function download(): StreamedResponse
    {
        $pasiens = User::where('role', 'pasien')->orderBy('name')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pasien');

        $headers = ['No', 'No. Rekam Medis', 'Nama Pasien', 'Email', 'Alamat', 'No. Telepon', 'Terdaftar Sejak'];
        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}1", $header);
            $sheet->getStyle("{$col}1")->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach ($pasiens as $i => $pasien) {
            $row = $i + 2;
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $pasien->no_rm ?? '-');
            $sheet->setCellValue("C{$row}", $pasien->name);
            $sheet->setCellValue("D{$row}", $pasien->email);
            $sheet->setCellValue("E{$row}", $pasien->alamat ?? '-');
            $sheet->setCellValue("F{$row}", $pasien->no_hp ?? '-');
            $sheet->setCellValue("G{$row}", $pasien->created_at->format('d/m/Y'));
        }

        $filename = 'data-pasien-' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
