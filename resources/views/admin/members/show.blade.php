<x-admin-layout title="Kartu Perpustakaan">
    <div class="d-flex justify-content-center my-5">
        <div id="card" style="width: 480px; height: 250px; border: 2px solid #333; border-radius: 8px; 
                    background-color: #fff; color: #333; font-family: Arial, sans-serif; 
                    position: relative; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); 
                    box-sizing: border-box; overflow: hidden;">
            
            {{-- Header --}}
            <div class="text-center mb-3" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                <h4 style="margin:0; font-weight:bold; font-size: 18px;">PERPUSTAKAAN MTS TANWIRIYYAH</h4>
                <small>Anggota Resmi</small>
            </div>

            {{-- Data Member --}}
            <div style="font-size: 13px; line-height: 1.3; word-wrap: break-word;">
                <p><strong>No. Anggota:</strong> {{ $member->number_type }}{{ $member->number }}</p>
                <p><strong>Nama:</strong> {{ Str::limit($member->name, 30, '...') }}</p>
                <p>
                    <strong>Jenis Kelamin:</strong> 
                    @if ($member->gender == 'Men' || 'Laki-laki')
                        Laki-laki
                    @elseif ($member->gender == 'Women' || 'Perempuan')
                        Perempuan
                    @else
                        -
                    @endif
                </p>

                <p><strong>Telepon:</strong> +{{ $member->telephone }}</p>
                <p><strong>Alamat:</strong> {{ Str::limit($member->address, 40, '...') }}</p>
            </div>

            {{-- QR Code --}}
            <div style="position: absolute; bottom: 60px; right: 50px; width: 70px; height: 70px;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode($member->name . "\n" . $member->number) }}" alt="QR Code">
            </div>

            {{-- Tanggal Terbit --}}
            <div style="position: absolute; bottom: 20px; right: 30px; font-size: 12px;">
                Terbit: {{ $member->created_at->format('d M Y') }}
            </div>
        </div>
    </div>

    {{-- Tombol Cetak --}}
    <div class="text-center">
        <button onclick="printCard()" class="btn btn-primary mt-3">Cetak Kartu</button>
        <a href="{{ route('admin.members.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    {{-- Script Cetak --}}
    <script>
        function printCard() {
            const printContents = document.getElementById('card').outerHTML;
            const w = window.open('', '', 'width=500,height=300');
            w.document.write('<html><head><title>Cetak Kartu</title></head><body>');
            w.document.write(printContents);
            w.document.write('</body></html>');
            w.document.close();
            w.focus();
            w.print();
            w.close();
        }
    </script>
</x-admin-layout>
