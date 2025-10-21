    <x-admin-layout title="Daftar Denda">
        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- Notifikasi sukses --}}
                @if ($success = session()->get('success'))
                    <div class="card border-left-success mb-3">
                        <div class="card-body">{!! $success !!}</div>
                    </div>
                @endif

                {{-- Judul halaman --}}
                <h4 class="mb-4 font-weight-bold">Daftar Denda</h4>

            <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th>Buku</th>
                    <th>Hari Terlambat</th>
                    <th>Jumlah Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fines as $fine)
                    <tr>
                        <td>{{ $fine->borrow->book->title ?? '-' }}</td>
                        <td class="text-center">{{ $fine->days_late }} hari</td>
                        <td class="text-center">Rp{{ number_format($fine->amount, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($fine->is_paid)
                                <span class="badge badge-success">Sudah Dibayar</span>
                            @else
                                <span class="badge badge-danger">Belum Dibayar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (!$fine->is_paid)
                                <form action="{{ route('fines.pay', $fine->id) }}" method="POST"
                                    onsubmit="return confirm('Tandai denda ini sebagai sudah dibayar?')"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Bayar Denda
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    Sudah Dibayar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada denda</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3 pt-2" style="border-top: 1px solid #ddd;">
            <h5>Total Denda: <strong>Rp{{ number_format($totalAmount, 0, ',', '.') }}</strong></h5>
        </div>


        {{-- Pagination --}}
        @if(method_exists($fines, 'links'))
            <div class="mt-4">
                {{ $fines->withQueryString()->links() }}
            </div>
        @endif
    </div>

    </div>
</x-admin-layout>
