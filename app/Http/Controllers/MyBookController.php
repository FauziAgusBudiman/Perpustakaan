<?php

namespace App\Http\Controllers;

use App\Imports\BookImport;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Restore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MyBookController extends Controller
{
    public function index()
    {
        $currentBorrows = Borrow::query()
            ->with('book')
            ->whereBelongsTo(Auth::user())
            ->whereDoesntHave('restore', function (Builder $query) {
                $query->where('confirmation', true);
            })
            ->latest('id')
            ->paginate(6);

        $recentBorrows = Borrow::query()
            ->with(['book', 'restore'])
            ->whereBelongsTo(Auth::user())
            ->whereHas('restore', function (Builder $query) {
                $query->where('confirmation', true);
            })
            ->latest('id')
            ->limit(6)
            ->get();

        return view('my-books')->with([
            'currentBorrows' => $currentBorrows,
            'recentBorrows' => $recentBorrows,
        ]);
    }

        public function store(Request $request, Book $book)
        {
            $request->validate([
                'duration' => ['required', 'numeric'],
                'amount' => ['required', 'numeric', 'max:' . $book->amount],
            ]);

            Borrow::create([
                'borrowed_at' => now(),
                'duration' => $request->duration,
                'amount' => $request->amount,
                'confirmation' => false,
                'book_id' => $book->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('my-books.index')->with('success', 'Berhasil mengajukan peminjaman!');
        }

//     //test denda
//     public function store(Request $request, Book $book)
// {
//     $request->validate([
//         'duration' => ['required', 'numeric'],
//         'amount' => ['required', 'numeric', 'max:' . $book->amount],
//     ]);

//     Borrow::create([
//         // Ganti tanggal sekarang dengan 15 Oktober 2025
//         'borrowed_at' => Carbon::parse('2025-10-15 10:00:00'), 
//         'duration' => $request->duration,
//         'amount' => $request->amount,
//         'confirmation' => false,
//         'book_id' => $book->id,
//         'user_id' => Auth::id(),
//     ]);

//     return redirect()->route('my-books.index')
//         ->with('success', 'Berhasil mengajukan peminjaman dengan tanggal 15 Oktober!');
// }

    public function update($id)
{
    $borrow = Borrow::query()->findOrFail($id);

    // Cegah jika belum dikonfirmasi atau sudah dikembalikan
    if (!$borrow->confirmation || isset($borrow->restore)) {
        return back()->withErrors('Peminjaman ini belum dikonfirmasi atau sudah dikembalikan!');
    }
    // Hitung tanggal jatuh tempo
    $dueDate = $borrow->borrowed_at->copy()->addDays($borrow->duration);
    
    // Tentukan status pengembalian (Past due atau Not confirmed)
    $isLate = now()->greaterThan($dueDate);


    $returnStatus = $isLate
        ? Restore::STATUSES['Past due']
        : Restore::STATUSES['Not confirmed'];


   $lateDays = $isLate
    ? $dueDate->startOfDay()->diffInDays(now()->startOfDay()): 0;
    $finePerDay = 1000;
    $totalFine = $lateDays * $finePerDay;


    // Simpan data pengembalian
    Restore::create([
        'returned_at' => now(),
        'status' => $returnStatus,
        'confirmation' => false, // belum dikonfirmasi admin
        'book_id' => $borrow->book_id,
        'user_id' => Auth::id(),
        'borrow_id' => $borrow->id,
        'fine' => $totalFine,
    ]);

    return redirect()
        ->route('my-books.index')
        ->with('success', $isLate
            ? "Berhasil mengajukan pengembalian. Terlambat $lateDays hari. Denda Rp" . number_format($totalFine, 0, ',', '.')
            : "Berhasil mengajukan pengembalian tanpa denda.");
}


    public function show(Book $book)
        {
            // Bisa ditampilkan di view khusus
            return view('admin.books.show', compact('book'));
        }

        public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new BookImport, $request->file('file'));

        return redirect()->route('admin.books.index')
                        ->with('success', 'Data buku berhasil diimport!');
    }
}
