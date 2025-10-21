<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Borrow;
use App\Models\Restore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
  public function index()
{
    $fines = Fine::with(['borrow.book'])->paginate(10);

    $totalAmount = Fine::sum('amount');

    return view('fines', compact('fines', 'totalAmount'));
}



public function pay($id)
{
    $fine = Fine::findOrFail($id);

    if ($fine->is_paid) {
        return back()->withErrors('Denda ini sudah dibayar.');
    }

    // Update status denda
    $fine->update([
        'is_paid' => true,
        'paid_at' => now(), // kalau kamu punya kolom paid_at
    ]);

    // Jika denda terkait dengan pengembalian (restore), ubah statusnya juga
    if ($fine->borrow && $fine->borrow->restore) {
        $fine->borrow->restore->update([
            'status' => Restore::STATUSES['Fine paid'],
        ]);
    }

    return back()->with('success', 'Denda berhasil dibayar dan status pengembalian diperbarui.');
}


}
