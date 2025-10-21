<x-app-layout>
    {{-- HERO / WELCOME SECTION --}}
    <section class="d-flex flex-column justify-content-center align-items-center text-center bg-light mt-5 py-5 px-4 rounded-3 shadow-sm">
        <h1 class="mt-4 fs-1 fw-bold text-primary">Selamat Datang di Perpustakaan MTs Tanwiriyyah</h1>
        <p class="text-secondary mt-2 mb-4">Temukan dan pinjam berbagai koleksi buku terbaik kami dengan mudah.</p>

        {{-- Search Bar --}}
        <form action="{{ route('search') }}" method="GET" class="d-flex position-relative w-100" style="max-width: 600px;">
            <input type="text" name="search" class="form-control form-control-lg rounded-pill ps-4"
                placeholder="🔍 Cari buku berdasarkan judul atau penulis...">
            <button type="submit"
                class="btn btn-primary position-absolute end-0 me-2 rounded-pill px-4 fw-semibold">
                Cari
            </button>
        </form>
    </section>

    {{-- POPULAR BOOKS SECTION --}}
    <section class="py-5 bg-white">
        <h2 class="fs-3 fw-bold text-center mb-4 text-primary">📚 Buku Paling Populer</h2>

        <div class="container">
            @if ($popularBooks->isEmpty())
                <p class="text-center text-muted">Belum ada buku populer yang tersedia.</p>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach ($popularBooks as $popularBook)
                        <div class="col">
                            <a href="{{ route('preview', $popularBook) }}" class="text-decoration-none text-dark">
                                <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                                    <img src="{{ isset($popularBook->cover) ? asset('storage/' . $popularBook->cover) : asset('storage/placeholder.png') }}"
                                        alt="{{ $popularBook->title }}" class="card-img-top rounded-top">
                                    <div class="card-body text-center">
                                        <h5 class="fw-bold mb-2">{{ $popularBook->title }}</h5>
                                        <span class="text-muted small">Dipinjam
                                            <span class="fw-semibold text-primary">{{ $popularBook->borrows_count }}</span> kali
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- NEWEST BOOKS SECTION --}}
    <section class="py-5 bg-light">
        <h2 class="fs-3 fw-bold text-center mb-4 text-primary">🆕 Koleksi Terbaru</h2>

        <div class="container">
            @if ($newestBooks->isEmpty())
                <p class="text-center text-muted">Belum ada buku baru yang ditambahkan.</p>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach ($newestBooks as $newestBook)
                        <div class="col">
                            <a href="{{ route('preview', $newestBook) }}" class="text-decoration-none text-dark">
                                <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                                    <img src="{{ isset($newestBook->cover) ? asset('storage/' . $newestBook->cover) : asset('storage/placeholder.png') }}"
                                        alt="{{ $newestBook->title }}" class="card-img-top rounded-top">
                                    <div class="card-body text-center">
                                        <h5 class="fw-bold mb-2">{{ $newestBook->title }}</h5>
                                        <span class="text-muted small">
                                            Terbit {{ $newestBook->created_at->locale('id_ID')->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CSS tambahan --}}
    <style>
        .hover-shadow:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</x-app-layout>
