<x-app-layout>
    {{-- HERO / WELCOME SECTION --}}
    <section class="hero-section text-center d-flex flex-column justify-content-center align-items-center py-5 px-4 rounded-3 shadow-sm">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="mt-4 fs-1 fw-bold hero-title">
                📚 Selamat Datang di Perpustakaan <br> MTs Tanwiriyyah
            </h1>
            <p class="hero-subtitle mt-3 mb-4">
                Temukan dan pinjam berbagai koleksi buku terbaik kami dengan mudah!
            </p>

            {{-- Search Bar --}}
            <form action="{{ route('search') }}" method="GET" class="search-form mx-auto">
                <input type="text" name="search" class="form-control form-control-lg"
                    placeholder="🔍 Cari buku berdasarkan judul atau penulis...">
                <button type="submit" class="btn btn-light text-primary">
                    Cari
                </button>
            </form>
        </div>
    </section>

    {{-- POPULAR BOOKS SECTION --}}
    <section class="py-5 bg-white">
        <h2 class="fs-3 fw-bold text-center mb-5 text-primary">🔥 Buku Paling Populer</h2>

        <div class="container">
            @if ($popularBooks->isEmpty())
                <p class="text-center text-muted">Belum ada buku populer yang tersedia.</p>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach ($popularBooks as $popularBook)
                        <div class="col">
                            <a href="{{ route('preview', $popularBook) }}" class="text-decoration-none text-dark">
                                <div class="card border-0 shadow-lg book-card h-100 p-2">
                                    <div class="book-cover-wrapper position-relative">
                                        <img src="{{ isset($popularBook->cover) ? asset('storage/' . $popularBook->cover) : asset('storage/placeholder.png') }}"
                                            alt="{{ $popularBook->title }}" class="book-cover rounded-3">
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold book-title text-primary mb-1">{{ $popularBook->title }}</h6>
                                        <!-- <p class="text-muted small mb-1">✍️ {{ $popularBook->author ?? 'Tidak diketahui' }}</p>
                                        <p class="text-secondary small"> -->
                                            <!-- {{ Str::limit($popularBook->description ?? 'Belum ada sinopsis.', 80, '...') }} -->
                                        </p>
                                        <div class="text-muted small mt-2">
                                            📖 <span class="fw-semibold text-primary">{{ $popularBook->borrows_count }}</span> kali dipinjam
                                        </div>
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
        <h2 class="fs-3 fw-bold text-center mb-5 text-success">🆕 Koleksi Buku Terbaru</h2>

        <div class="container">
            @if ($newestBooks->isEmpty())
                <p class="text-center text-muted">Belum ada buku baru yang ditambahkan.</p>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach ($newestBooks as $newestBook)
                        <div class="col">
                            <a href="{{ route('preview', $newestBook) }}" class="text-decoration-none text-dark">
                                <div class="card border-0 shadow-lg book-card h-100 p-2">
                                    <div class="book-cover-wrapper position-relative">
                                        <img src="{{ isset($newestBook->cover) ? asset('storage/' . $newestBook->cover) : asset('storage/placeholder.png') }}"
                                            alt="{{ $newestBook->title }}" class="book-cover rounded-3">
                                        <span class="badge bg-success position-absolute top-0 end-0 m-2">Baru</span>
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold book-title text-success mb-1">{{ $newestBook->title }}</h6>
                                        <!-- <p class="text-muted small mb-1">✍️ {{ $newestBook->author ?? 'Tidak diketahui' }}</p> -->
                                        <p class="text-secondary small">
                                            <!-- {{ Str::limit($newestBook->description ?? 'Belum ada sinopsis.', 80, '...') }} -->
                                        </p>
                                        <div class="text-muted small mt-2">
                                            🕒 {{ $newestBook->created_at->locale('id_ID')->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </a>
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
            background: linear-gradient(135deg, #007bff 0%, #5f2eea 100%);
            color: white;
            min-height: 350px;
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
        }

        .hero-title {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.35);
            font-weight: 800;
            letter-spacing: 0.6px;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.25);
            font-size: 1.2rem;
        }

        .search-form {
            position: relative;
            width: 100%;
            max-width: 600px;
        }

        .search-form input {
            border-radius: 50px;
            padding-left: 2.5rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .search-form button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-content {
            animation: fadeIn 1.2s ease-in-out;
        }

        /* Card style */
        .book-card {
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
            background-color: #ffffff;
        }

        .book-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        /* Book cover */
        .book-cover-wrapper {
            height: 250px;
            overflow: hidden;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
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

        .book-title {
            font-size: 15px;
            line-height: 1.4;
        }
    </style>
</x-app-layout>
