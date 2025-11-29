<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Restore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $borrows = Borrow::with(['book', 'user']);

        $borrows->when($request->search, function (Builder $query) use ($request) {
            $query->where(function (Builder $q) use ($request) {
                $q->whereHas('book', function (Builder $query) use ($request) {
                    $query->where('title', 'LIKE', "%{$request->search}%");
                })
                    ->orWhereHas('user', function (Builder $query) use ($request) {
                        $query->where('name', 'LIKE', "%{$request->search}%");
                    });
            });
        });

        $borrows = $borrows->latest('id')->paginate(10);

        return view('admin.borrows.index')->with([
            'borrows' => $borrows,
        ]);
    }

    public function edit(Borrow $borrow)
    {
        return view('admin.borrows.edit')->with([
            'borrow' => $borrow,
        ]);
    }

    public function update(Request $request, Borrow $borrow)
    {
        $data = $request->validate([
            'confirmation' => ['required', Rule::in([1])],
        ]);

        // jika peminjaman belum terkonfirmasi kemudian saat ini dikonfirmasi
        if (!$borrow->confirmation) {
            $borrow->book()->decrement('amount', $borrow->amount);
        }
   // Simpan data pengembalian
            Restore::create([
                'returned_at' => now(),
                'status' => Restore::STATUSES['Not confirmed'],
                'confirmation' => false, // belum dikonfirmasi admin
                'book_id' => $borrow->book_id,
                'user_id' => Auth::id(),
                'borrow_id' => $borrow->id,
                'fine' => null,
            ]);
        $borrow->update($data);

        return redirect()
            ->route('admin.borrows.index')
            ->with('success', 'Berhasil mengubah status konfirmasi peminjaman.');
    }

    public function destroy(Borrow $borrow)
    {
        $borrow->delete();

        return redirect()
            ->route('admin.borrows.index')
            ->with('success', 'Berhasil menghapus peminjaman.');
    }
    public function denda()
    {
       $borrows = Borrow::with(['user', 'book', 'restore'])
            ->where('confirmation', 1)
            ->whereHas('restore', function ($query) {
                $query->whereIn('status', [
                    'Belum dikonfirmasi',
                    'Terlambat'
                ]);
            })
            ->get();


    foreach ($borrows as $borrow) {
        $borrowDate = \Carbon\Carbon::parse($borrow->borrowed_at);
        $dueDate = $borrowDate->copy()->addDays($borrow->duration);
        $daysRemaining = (int) now()->diffInDays($dueDate, false);

        $user = $borrow->user;
        $book = $borrow->book;

        if (!$user || !$book || !$user->telephone) {
            continue;
        }

        $phone = $user->telephone;

        // ⚙️ Jika sudah lewat jatuh tempo
        if ($daysRemaining < 0) {
            $daysLate = abs((int) $daysRemaining);
            $fineAmount = $daysLate * 5000;

            // 🔍 Cek apakah sudah ada denda
            $existingFine = \App\Models\Fine::where('borrow_id', $borrow->id)->first();

            if (!$existingFine) {
                \App\Models\Fine::create([
                    'borrow_id' => $borrow->id,
                    'user_id' => $user->id,
                    'days_late' => $daysLate,
                    'amount' => $fineAmount,
                    'is_paid' => false,
                ]);
            } else {
                $existingFine->update([
                    'days_late' => $daysLate,
                    'amount' => $fineAmount,
                ]);
            }

            // 🧩 Update atau buat restore dengan total denda
            $restore = \App\Models\Restore::where('borrow_id', $borrow->id)->first();

            if ($restore) {
                $restore->update(['fine' => $fineAmount]);
            } else {
                \App\Models\Restore::create([
                    // 'returned_at' => now(),
                    'status' => 'Not confirmed',
                    'confirmation' => false,
                    'book_id' => $borrow->book_id,
                    'user_id' => $user->id,
                    'borrow_id' => $borrow->id,
                    'fine' => $fineAmount, // 💰 total denda dikirim ke sini
                ]);
            }

            // Kirim pesan WA
            $message = "Halo {$user->name},\n\n"
                . "⚠️ *Peringatan Keterlambatan Pengembalian Buku*\n\n"
                . "Buku *{$book->title}* telah terlambat *{$daysLate} hari* "
                . "(jatuh tempo: {$dueDate->format('Y-m-d')}).\n\n"
                . "Total denda sementara: *Rp {$fineAmount}*.\n"
                . "Harap segera dikembalikan agar denda tidak bertambah.\n\n"
                . "Terima kasih 🙏\nAdmin Perpustakaan";

            $this->sendFonnteMessage($phone, $message);
        }

        // ⚙️ Jika H-1 atau hari H jatuh tempo
        elseif ($daysRemaining <= 1 && $daysRemaining >= 0) {
            $message = "Halo {$user->name},\n\n"
                . "📚 *Pengingat Pengembalian Buku*\n\n"
                . "Buku *{$book->title}* akan jatuh tempo Besok* "
                . "(tanggal pengembalian: {$dueDate->format('Y-m-d')}).\n\n"
                . "Hindari denda sebesar *Rp 5000 per hari* dengan mengembalikan tepat waktu.\n\n"
                . "Terima kasih 🙏\nAdmin Perpustakaan";

            $this->sendFonnteMessage($phone, $message);
        }
    }

    return back()->with('success', 'Pesan pengingat dan data denda berhasil diproses serta dikirim ke tabel restore.');
}




    /**
     * Fungsi kirim pesan via Fonnte API
     */
    private function sendFonnteMessage($target, $message)
    {
        $token = "SJboRBB5m1tiJdMcY4QM";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

    if ($err) {
    Log::error("Fonnte Error: $err");
} else {
    Log::info("Fonnte Response: $response");
}
    }
}