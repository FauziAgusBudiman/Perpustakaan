<x-admin-layout title="Label Buku">
    <div class="d-flex justify-content-center my-5">
        <div id="book-label" style="width: 350px; min-height: 180px; border: 2px solid #333; 
                    background-color: #fff; border-radius: 8px; 
                    padding: 20px; font-family: Arial, sans-serif; 
                    position: relative; box-shadow: 0 2px 6px rgba(0,0,0,0.2); box-sizing: border-box;">

            {{-- Header --}}
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" 
                         style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px; margin-right: 10px; border:1px solid #ccc;">
                @endif
                <h5 style="margin:0; font-size:18px; font-weight:bold; color:#333;">{{ Str::limit($book->title, 35) }}</h5>
            </div>

            {{-- Info Buku --}}
            <div style="font-size:13px; line-height:1.4; color:#333;">
                <p style="margin:2px 0;"><strong>Penulis:</strong> {{ $book->writer }}</p>
                <p style="margin:2px 0;"><strong>Penerbit:</strong> {{ $book->publisher }}</p>
                <p style="margin:2px 0;"><strong>Tahun:</strong> {{ $book->publish_year }}</p>
                <p style="margin:2px 0;"><strong>Kategori:</strong> {{ $book->category ?? '-' }}</p>
                <p style="margin:2px 0;"><strong>No Rak:</strong> {{ $book->rack_number ?? '-' }}</p>
            </div>

            {{-- QR Code --}}
            <div style="position: absolute; bottom: 15px; right: 15px; width: 90px; height: 90px; border: 1px solid #ccc; border-radius: 5px; padding:2px; background:#fff;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($book->title . ' | ' . $book->writer . ' | ' . $book->publish_year) }}" 
                     alt="QR Code" style="width:100%; height:100%;">
            </div>

            {{-- Tanggal dibuat --}}
            <div style="position: absolute; bottom: 5px; left: 20px; font-size: 12px; color:#555;">
                Dibuat: {{ $book->created_at->format('d M Y') }}
            </div>

        </div>
    </div>

    {{-- Tombol Cetak --}}
    <div class="text-center">
        <button onclick="printLabel()" class="btn btn-primary mt-3">Cetak Label</button>
    </div>

    {{-- Script Cetak --}}
    <script>
        function printLabel() {
            const printContents = document.getElementById('book-label').outerHTML;
            const w = window.open('', '', 'width=400,height=400');
            w.document.write('<html><head><title>Cetak Label Buku</title></head><body>');
            w.document.write(printContents);
            w.document.write('</body></html>');
            w.document.close();
            w.focus();
            w.print();
            w.close();
        }
    </script>
</x-admin-layout>
