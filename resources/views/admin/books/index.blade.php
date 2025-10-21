<x-admin-layout title="List Buku">
    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($success = session()->get('success'))
                <div class="card border-left-success">
                    <div class="card-body">{!! $success !!}</div>
                </div>
            @endif

            <div class="mb-3 d-flex gap-3 align-items-center">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah
            </a>

            <form action="{{ route('admin.books.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 mb-0 ml-2">
                @csrf
                <input type="file" name="file" class="form-control" style="max-width: 270px;" required>
                <button type="submit" class="btn btn-success ml-2">
                    <i class="fas fa-file-excel"></i> Import Excel
                </button>
            </form>
        </div>



            <x-admin.search url="{{ route('admin.books.index') }}" placeholder="Cari buku..." />

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            {{-- <th>No Rak</th> --}}
                            <th>Tahun Terbit</th>
                            <th>Jumlah Tersedia</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $book)
                            <tr>
                                <td>
                                    <img src="{{ isset($book->cover) ? asset('storage/' . $book->cover) : asset('storage/placeholder.png') }}"
                                        alt="{{ $book->title }}" class="rounded" style="width: 100px;">
                                </td>
                                <td>{{ $book->category }}</td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->writer }}</td>
                                <td>{{ $book->publisher }}</td>
                                {{-- <td>{{ $book->rack_number ?? '-' }}</td> --}}
                                <td>{{ $book->publish_year }}</td>
                                <td>{{ $book->amount }} buku</td>
                                <td>
                                    @switch($book->status)
                                        @case(\App\Models\Book::STATUSES['Available'])
                                            <span class="badge badge-success">Tersedia</span>
                                        @break

                                        @case(\App\Models\Book::STATUSES['Borrowed'])
                                            <span class="badge badge-warning">Dipinjam</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('admin.books.edit', $book) }}"
                                        class="btn btn-link">Edit</a>
                                        <a href="{{ route('admin.books.show', $book) }}"
                                          class="btn btn-link">Cetak Label</a>

                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-link text-danger">Hapus</button>
                                        
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $books->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
