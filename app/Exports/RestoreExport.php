<?php

namespace App\Exports;

use App\Models\Restore;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RestoreExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Restore::with(['book', 'user', 'borrow'])->get()->map(function ($restore) {
            return [
                'Buku' => $restore->book->title,
                'Peminjam' => $restore->user->name,
                'Tanggal Peminjaman' => $restore->borrow ? $restore->borrow->borrowed_at->format('d-m-Y') : '-',
                'Tanggal Pengembalian' => $restore->returned_at->format('d-m-Y'),
                'Denda' => $restore->fine ?? '-',
                'Status' => $restore->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Buku',
            'Peminjam',
            'Tanggal Peminjaman',
            'Tanggal Pengembalian',
            'Denda',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FFEFEFEF'],
            ],
        ]);

        // Border seluruh cell
        $sheet->getStyle('A1:F' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin'
                ]
            ]
        ]);

        // Ratakan teks tanggal & angka di tengah
        $sheet->getStyle('C2:E' . $sheet->getHighestRow())->getAlignment()->setHorizontal('center');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Buku
            'B' => 25, // Peminjam
            'C' => 20, // Tanggal Peminjaman
            'D' => 20, // Tanggal Pengembalian
            'E' => 10, // Denda
            'F' => 15, // Status
        ];
    }
}
