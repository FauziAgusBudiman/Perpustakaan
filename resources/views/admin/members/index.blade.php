<x-admin-layout title="Daftar Siswa">
    <div class="card shadow mb-4">
        <div class="card-body">

            @if ($success = session()->get('success'))
                <div class="card border-left-success">
                    <div class="card-body">{!! $success !!}</div>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tipe Nomor</th>
                            <th>Nomor</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->number_type }}</td>
                                <td>{{ $member->number }}</td>
                                <td>+{{ $member->telephone }}</td>

                                <td class="d-flex">

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.members.edit', $member) }}"
                                    class="btn btn-sm btn-outline-primary mx-1 d-flex align-items-center">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus member ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger mx-1 d-flex align-items-center">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>

                                    {{-- Tombol Cetak Kartu --}}
                                    <a href="{{ route('admin.members.show', $member) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success mx-1 d-flex align-items-center">
                                        <i class="bi bi-printer me-1"></i> Cetak Kartu
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-5">
                    {{ $members->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
