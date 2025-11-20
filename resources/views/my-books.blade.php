<x-app-layout>
    {{-- HERO SECTION UNTUK HALAMAN MY BOOK --}}
    <section class="hero-section text-center d-flex flex-column justify-content-center align-items-center py-5 px-4 rounded-3 shadow-sm">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="mt-4 fs-1 fw-bold hero-title">
                📖 Buku Saya
            </h1>
            <p class="hero-subtitle mt-3 mb-4">
                Lihat koleksi buku yang sedang kamu pinjam dan riwayat peminjamanmu di sini.
            </p>
        </div>
    </section>

    <!-- BAGIAN BUKU YANG SEDANG DIPINJAM -->
    <section class="mt-5 py-5">
        {{-- Pesan sukses --}}
        @if ($message = session()->get('success'))
            <div class="container mb-3">
                <div class="alert alert-success fw-semibold shadow-sm">
                    {{ $message }}
                </div>
            </div>
        @endif

        {{-- Pesan error umum --}}
        @error('default')
            <div class="container mb-3">
                <div class="alert alert-danger fw-semibold shadow-sm">
                    {{ $message }}
                </div>
            </div>
        @enderror

        <div class="container">
            <h2 class="mt-4 fs-4 fw-bold mb-4 text-primary">📚 Sedang Dipinjam</h2>

            @if ($currentBorrows->isEmpty())
                <div class="text-center text-muted py-5">
                    <img src="{{ asset('storage/placeholder.png') }}" alt="Tidak ada pinjaman" width="180" class="mb-3 opacity-75">
                    <p>Belum ada buku yang sedang kamu pinjam.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($currentBorrows as $currentBorrow)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-lg book-card">
                                <div class="book-cover-wrapper position-relative">
                                    <img src="{{ $currentBorrow->book->cover ? asset('storage/' . $currentBorrow->book->cover) : asset('storage/placeholder.png') }}"
                                        alt="{{ $currentBorrow->book->title }}" class="book-cover rounded-top-3">
                                </div>

                                <div class="card-body text-center">
                                    <h5 class="fw-bold text-primary mb-1">{{ $currentBorrow->book->title }}</h5>
                                    <p class="text-muted small mb-2">✍️ {{ $currentBorrow->book->author ?? 'Tidak diketahui' }}</p>

                                    {{-- Tenggat --}}
                                    @php
                                        $due = $currentBorrow->borrowed_at->addDays($currentBorrow->duration);
                                    @endphp
                                    <p class="mb-2">
                                        Tenggat:
                                        <span class="fw-bold text-decoration-underline text-{{ $due > now() ? 'success' : 'danger' }}">
                                            {{ $due->locale('id_ID')->diffForHumans() }}
                                        </span>
                                    </p>

                                    {{-- Status --}}
                                    <div class="mt-3">
                                        @if (!$currentBorrow->confirmation)
                                            <span class="badge bg-warning text-dark">Belum dikonfirmasi</span>
                                        @else
                                            @switch($currentBorrow->restore?->status)
                                                @case(\App\Models\Restore::STATUSES['Not confirmed'])
                                                @case(\App\Models\Restore::STATUSES['Past due'])
                                                    <span class="badge bg-secondary">Menunggu konfirmasi pengembalian</span>
                                                    @break
                                                @case(\App\Models\Restore::STATUSES['Fine not paid'])
                                                    <span class="badge bg-danger">
                                                        Denda: Rp{{ number_format($currentBorrow->restore->fine, 0, ',', '.') }},-
                                                    </span>
                                                    @break
                                                @default
                                                    <form action="{{ route('my-books.update', $currentBorrow) }}" method="POST"
                                                        onsubmit="return confirm('Anda yakin ingin mengembalikan buku ini?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                            Kembalikan Buku
                                                        </button>
                                                    </form>
                                            @endswitch
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $currentBorrows->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- BAGIAN RIWAYAT PEMINJAMAN -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="fs-4 fw-bold mb-4 text-success">🕓 Riwayat Peminjaman</h2>

            @if ($recentBorrows->isEmpty())
                <div class="text-center text-muted py-5">
                    <img src="{{ asset('storage/placeholder.png') }}" alt="Tidak ada riwayat" width="180" class="mb-3 opacity-75">
                    <p>Belum ada riwayat peminjaman buku.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($recentBorrows as $recentBorrow)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-lg book-card">
                                <div class="book-cover-wrapper">
                                    <img src="{{ $recentBorrow->book->cover ? asset('storage/' . $recentBorrow->book->cover) : asset('storage/placeholder.png') }}"
                                        alt="{{ $recentBorrow->book->title }}" class="book-cover rounded-top-3">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="fw-bold text-success mb-1">{{ $recentBorrow->book->title }}</h5>
                                    <p class="text-muted small mb-2">✍️ {{ $recentBorrow->book->author ?? 'Tidak diketahui' }}</p>
                                    <p class="text-secondary small">
                                        Dikembalikan pada:
                                        <span class="fw-semibold text-dark">
                                            {{ $recentBorrow->restore->returned_at->locale('id_ID')->isoFormat('LL') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CSS TAMBAHAN --}}
    <style>
        /* Hero section */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
            min-height: 280px;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.1));
            z-index: 1;
        }

        .hero-content {
            z-index: 2;
            position: relative;
            animation: fadeIn 1.2s ease-in-out;
        }

        .hero-title {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.35);
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.25);
            font-size: 1.15rem;
        }

        /* Book cards */
        .book-card {
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
            background-color: #ffffff;
        }

        .book-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .book-cover-wrapper {
            height: 250px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px 15px 0 0;
        }

        .book-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .book-card:hover .book-cover {
            transform: scale(1.05);
        }

        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
