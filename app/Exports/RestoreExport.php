<?php

namespace App\Exports;

use App\Models\Borrow;
use App\Models\Restore;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RestoreExport implements FromCollection, WithHeadings
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
        return ['Buku', 'Peminjam', 'Tanggal Peminjaman', 'Tanggal Pengembalian', 'Denda', 'Status'];
    }


  
}

