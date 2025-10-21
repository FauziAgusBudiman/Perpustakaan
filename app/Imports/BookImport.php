<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BookImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Book([
            'category'     => $row['category'],
            'title'        => $row['title'],
            'writer'       => $row['writer'],
            'publisher'    => $row['publisher'],
            'publish_year' => $row['publish_year'],
            'amount'       => $row['amount'],
            // 'rack_number'  => $row['rack_number'], 
            'status'       => $row['status'] ?? \App\Models\Book::STATUSES['Available'],
        ]);
    }
}
