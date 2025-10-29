<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RestoreExport;
use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Models\Restore;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class RestoreController extends Controller
{
    public function index(Request $request)
    {
        $restores = Restore::with(['book', 'user']);

        $restores->when($request->search, function (Builder $query) use ($request) {
            $query->where(function (Builder $q) use ($request) {
                $q->whereHas('book', function (Builder $query) use ($request) {
                    $query->where('title', 'LIKE', "%{$request->search}%");
                })
                    ->orWhereHas('user', function (Builder $query) use ($request) {
                        $query->where('name', 'LIKE', "%{$request->search}%");
                    });
            });
        });

        $restores = $restores->latest('id')->paginate(10);

        return view('admin.returns.index')->with([
            'restores' => $restores,
        ]);
    }

    public function edit($id)
    {
        $restore = Restore::query()->findOrFail($id);

        return view('admin.returns.edit')->with([
            'restore' => $restore,
        ]);
    }

public function update(Request $request, $id)
{
    $restore = Restore::query()->findOrFail($id);

    $data = $request->validate([
        'confirmation' => ['required', 'boolean'],
    ]);

    $borrow = $restore->borrow;

    // // Hitung tanggal jatuh tempo dan tanggal dikembalikan
    // $dueDate = Carbon::parse($borrow->borrowed_at)->addDays($borrow->duration)->startOfDay();
    // $returnedAt = Carbon::parse($restore->returned_at)->startOfDay();

    // Ambil model buku
    $book = $restore->book;

    // Tambah stok buku sesuai jumlah yang dikembalikan
    $book->increment('amount', $borrow->amount);

    // ✅ Perbarui status buku berdasarkan stok terbaru
    if ($book->amount > 0) {
        $book->update(['status' => 'Tersedia']);
    } else {
        $book->update(['status' => 'Habis']);
    }

    // Tandai pengembalian sudah selesai
    $data['status'] = Restore::STATUSES['Returned'];

    // Simpan perubahan restore
    $restore->update($data);

    return redirect()
        ->route('admin.returns.index')
        ->with('success', 'Pengembalian buku berhasil diproses dan status buku diperbarui.');
}



    public function destroy($id)
    {
        $restore = Restore::query()->findOrFail($id);

        $restore->delete();

        return redirect()
            ->route('admin.returns.index')
            ->with('success', 'Berhasil menghapus pengembalian.');
    }


        public function exportExcel()
    {
        return Excel::download(new RestoreExport, 'pengembalian.xlsx');
    }

    /**
     * Export data pengembalian ke PDF
     */
    public function exportPdf()
    {
        $restores = Restore::with(['book', 'user', 'borrow'])->get();
        $pdf = Pdf::loadView('admin.returns.pdf', compact('restores'));
        return $pdf->download('pengembalian.pdf');
    }
}
