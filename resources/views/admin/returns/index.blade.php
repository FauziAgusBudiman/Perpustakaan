<x-admin-layout title="Daftar Pengembalian">
    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($success = session()->get('success'))
                <div class="card border-left-success mb-3">
                    <div class="card-body">{!! $success !!}</div>
                </div>
            @endif

            <div class="mb-3 d-flex gap-4">
                <a href="{{ route('admin.returns.exportExcel') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.returns.exportPdf') }}" class="btn btn-danger ml-2" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
            <x-admin.search url="{{ route('admin.returns.index') }}" placeholder="Cari pengembalian..." />


            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th>Durasi Peminjaman</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($restores as $restore)
                            <tr>
                                <td>
                                    <img src="{{ isset($restore->book->cover) ? asset('storage/' . $restore->book->cover) : asset('storage/placeholder.png') }}"
                                        alt="{{ $restore->book->title }}" class="rounded" style="width: 100px;">
                                    <span class="ml-3">{{ $restore->book->title }}</span>
                                </td>
                                <td>{{ $restore->user->name }}</td>
                                <td>{{ $restore->borrow->duration }} Hari</td>
                                <td>{{ $restore->borrow->borrowed_at->locale('id_ID')->isoFormat('LL') }}</td>
                                <td>{{ $restore->returned_at->locale('id_ID')->isoFormat('LL') }}</td>
                                <td>{{ $restore->fine ? $restore->fine : '-' }}</td>


                                <td>
                                    @switch($restore->status)
                                        @case(\App\Models\Restore::STATUSES['Returned'])
                                            <span class="badge badge-success">{{ $restore->status }}</span>    
                                        @break
                                    
                                        @case(\App\Models\Restore::STATUSES['Not confirmed'])
                                            <span class="badge badge-warning">{{ $restore->status }}</span>
                                        @break

                                        @case(\App\Models\Restore::STATUSES['Past due'])
                                            <span class="badge badge-danger">{{ $restore->status }}</span>
                                        @break

                                        @case(\App\Models\Restore::STATUSES['Fine not paid'])
                                            <span class="badge badge-dark">{{ $restore->status }}</span>
                                        @break
                                         @case(\App\Models\Restore::STATUSES['Fine paid'])
                                            <span class="badge badge-success">{{ $restore->status }}</span>
                                        @break
                                    @endswitch
                                </td>
                                

                                <td class="d-flex">

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.returns.edit', $restore) }}"
                                class="btn btn-sm btn-outline-primary mx-1 d-flex align-items-center">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.returns.destroy', $restore) }}" method="POST"
                                    onsubmit="return confirm('Anda yakin ingin menghapus pengembalian ini?')"
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
                        {{ $restores->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
