<x-admin-layout title="Daftar Peminjaman">
    <div class="card shadow mb-4">
        <div class="card-body">

            @if ($success = session()->get('success'))
                <div class="card border-left-success mb-3">
                    <div class="card-body">{!! $success !!}</div>
                </div>
            @endif

            {{-- Pencarian --}}
            <x-admin.search url="{{ route('admin.borrows.index') }}" placeholder="Cari peminjaman..." />

            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($borrows as $borrow)
                            <tr>
                                {{-- Buku + Gambar --}}
                                <td class="d-flex align-items-center">
                                    <img src="{{ isset($borrow->book->cover) ? asset('storage/' . $borrow->book->cover) : asset('storage/placeholder.png') }}"
                                         alt="{{ $borrow->book->title }}"
                                         class="rounded" style="width: 80px; height: auto;">
                                    <span class="ml-3">{{ $borrow->book->title }}</span>
                                </td>

                                {{-- Peminjam --}}
                                <td>{{ $borrow->user->name }}</td>

                                {{-- Tanggal --}}
                                <td>{{ $borrow->borrowed_at->locale('id_ID')->isoFormat('LL') }}</td>

                                {{-- Durasi --}}
                                <td>{{ $borrow->duration }} hari</td>

                                {{-- Status --}}
                                <td>
                                    @if ($borrow->confirmation)
                                        @if ($borrow->restore && $borrow->restore->status === \App\Models\Restore::STATUSES['Returned'])
                                            <span class="badge badge-success">
                                                <i class="bi bi-check-circle me-1"></i> Buku Dikembalikan
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="bi bi-book-half me-1"></i> Buku Dipinjam
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="bi bi-hourglass-split me-1"></i> Menunggu Konfirmasi
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="d-flex">

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.borrows.edit', $borrow) }}"
                                       class="btn btn-sm btn-outline-primary mx-1 d-flex align-items-center">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.borrows.destroy', $borrow) }}" method="POST"
                                          onsubmit="return confirm('Anda yakin ingin menghapus peminjaman ini?')"
                                          class="mx-1">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-5">
                    {{ $borrows->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
