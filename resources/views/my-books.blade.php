<x-app-layout>
    <!-- Bagian Buku yang Sedang Dipinjam -->
    <section class="mt-5 py-5">
        {{-- Pesan sukses --}}
        @if ($message = session()->get('success'))
            <div class="container mb-3">
                <div class="card bg-success-subtle p-3 text-success fw-semibold">
                    {{ $message }}
                </div>
            </div>
        @endif

        {{-- Pesan error umum --}}
        @error('default')
            <div class="container mb-3">
                <div class="card bg-danger-subtle p-3 text-danger fw-semibold">
                    {{ $message }}
                </div>
            </div>
        @enderror

        <div class="container">
            <h2 class="mt-4 fs-4 fw-bold mb-4">Sedang Dipinjam</h2>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                @foreach ($currentBorrows as $currentBorrow)
                    <a href="{{ route('preview', $currentBorrow->book) }}" class="col text-dark text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">

                                {{-- Status Peminjaman --}}
                                @if (!$currentBorrow->confirmation)
                                    <span class="text-warning">Belum dikonfirmasi</span>
                                @else
                                    @switch($currentBorrow->restore?->status)
                                        @case(\App\Models\Restore::STATUSES['Not confirmed'])
                                        @case(\App\Models\Restore::STATUSES['Past due'])
                                            <span class="text-secondary">
                                                Menunggu konfirmasi pengembalian...
                                            </span>
                                            @break

                                        @case(\App\Models\Restore::STATUSES['Fine not paid'])
                                            <span class="text-danger">
                                                Denda terlambat: Rp{{ number_format($currentBorrow->restore->fine, 0, ',', '.') }},-
                                            </span>
                                            @break

                                        @default
                                            <span class="text-success">Terkonfirmasi</span>
                                            <form action="{{ route('my-books.update', $currentBorrow) }}" method="POST"
                                                onsubmit="return confirm('Anda yakin ingin mengembalikan buku ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-link p-0 text-decoration-none text-primary">
                                                    Kembalikan
                                                </button>
                                            </form>
                                    @endswitch
                                @endif
                            </div>

                            {{-- Cover Buku --}}
                            <img src="{{ $currentBorrow->book->cover ? asset('storage/' . $currentBorrow->book->cover) : asset('storage/placeholder.png') }}"
                                alt="{{ $currentBorrow->book->title }}" class="card-img-top rounded-0"
                                style="height: 250px; object-fit: cover;">

                            {{-- Detail Buku --}}
                            <div class="card-body text-center">
                                <h3 class="card-text fs-5 fw-bold mb-3">
                                    {{ $currentBorrow->book->title }}
                                </h3>

                                {{-- Tenggat Waktu --}}
                                @php
                                    $due = $currentBorrow->borrowed_at->addDays($currentBorrow->duration);
                                @endphp
                                <span class="fs-6">Tenggat:
                                    <span class="fw-bold text-decoration-underline text-{{ $due > now() ? 'success' : 'danger' }}">
                                        {{ $due->locale('id_ID')->diffForHumans() }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $currentBorrows->links() }}
            </div>
        </div>
    </section>

    <!-- Bagian Riwayat Peminjaman -->
    <section class="py-5 bg-body-tertiary">
        <div class="container">
            <h2 class="fs-4 fw-bold mb-4">Peminjaman Terbaru Anda</h2>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                @foreach ($recentBorrows as $recentBorrow)
                    <a href="{{ route('preview', $recentBorrow->book) }}" class="col text-dark text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm">
                            {{-- Cover Buku --}}
                            <img src="{{ $recentBorrow->book->cover ? asset('storage/' . $recentBorrow->book->cover) : asset('storage/placeholder.png') }}"
                                alt="{{ $recentBorrow->book->title }}" class="card-img-top rounded-0"
                                style="height: 250px; object-fit: cover;">

                            {{-- Detail Buku --}}
                            <div class="card-body text-center">
                                <h3 class="card-text fs-5 fw-bold mb-3">
                                    {{ $recentBorrow->book->title }}
                                </h3>

                                <span class="fs-6">Meminjam tanggal
                                    <span class="fw-bold text-decoration-underline">
                                        {{ $recentBorrow->restore->returned_at->locale('id_ID')->isoFormat('LL') }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
