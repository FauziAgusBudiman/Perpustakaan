<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengembalian Buku - MTS TANWIRIYYAH</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .header-title {
            text-align: center;
            flex-grow: 1;
        }
        .header-title h2 {
            margin: 0;
            font-size: 16px;
        }
        .header-title p {
            margin: 0;
            font-size: 12px;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            color: white;
            font-size: 10px;
            text-align: center;
        }
        .success { background-color: #28a745; }
        .warning { background-color: #ffc107; }
        .danger  { background-color: #dc3545; }
        .dark    { background-color: #343a40; }
    </style>
</head>
<body>

    <!-- Header dengan logo -->
    <div class="header">
        <img src="{{ public_path('assets/images/Logo.png') }}" alt="Logo MTS TANWIRIYYAH" class="logo">
        <div class="header-title">
            <h2>MTS TANWIRIYYAH</h2>
            <p>Daftar Pengembalian Buku Perpustakaan</p>
        </div>
    </div>

    <!-- Tabel pengembalian -->
    <table>
        <thead>
            <tr>
                <th>Buku</th>
                <th>Peminjam</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($restores as $restore)
            <tr>
                <td>{{ $restore->book->title }}</td>
                <td>{{ $restore->user->name }}</td>
                <td>{{ $restore->borrow ? $restore->borrow->borrowed_at->format('d-m-Y') : '-' }}</td>
                <td>{{ $restore->returned_at->format('d-m-Y') }}</td>
                <td>{{ $restore->fine ?? '-' }}</td>
                <td>
                    @switch($restore->status)
                        @case(\App\Models\Restore::STATUSES['Returned'])
                            <span class="badge success">{{ $restore->status }}</span>
                        @break
                        @case(\App\Models\Restore::STATUSES['Not confirmed'])
                            <span class="badge warning">{{ $restore->status }}</span>
                        @break
                        @case(\App\Models\Restore::STATUSES['Past due'])
                            <span class="badge danger">{{ $restore->status }}</span>
                        @break
                        @case(\App\Models\Restore::STATUSES['Fine not paid'])
                            <span class="badge dark">{{ $restore->status }}</span>
                        @break
                        @case(\App\Models\Restore::STATUSES['Fine paid'])
                            <span class="badge success">{{ $restore->status }}</span>
                        @break
                        @default
                            <span class="badge dark">{{ $restore->status }}</span>
                    @endswitch
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: right; font-size: 10px;">Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>

</body>
</html>
