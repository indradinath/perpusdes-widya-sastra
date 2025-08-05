<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Buku</title>
    <style>
        body { font-family: sans-serif; font-size: 8pt; } /* Ukuran font lebih kecil karena banyak kolom */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: left; } /* Padding lebih kecil */
        th { background-color: #f2f2f2; }
        h1 { text-align: center; margin-bottom: 20px; }
        img { max-width: 40px; height: auto; display: block; margin: auto; } /* Untuk gambar sampul di PDF */
    </style>
</head>
<body>
    <h1>Laporan {{ $title }} Perpustakaan Desa Dauh Peken</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ISBN</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Avail</th>
                <th>Kategori</th>
                <th>Rak</th>
                <th>Sampul</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $key => $book)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $book->isbn ?? '-' }}</td>
                <td>{{ $book->judul }}</td>
                <td>{{ $book->penulis }}</td>
                <td>{{ $book->penerbit }}</td>
                <td>{{ $book->tahun_terbit }}</td>
                <td>{{ $book->jumlah_stok }}</td>
                <td>{{ $book->jumlah_tersedia }}</td>
                <td>{{ $book->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $book->rack->nama_rak ?? '-' }} ({{ $book->rack->kode_rak ?? '-' }})</td>
                <td>
                    @if ($book->gambar_sampul)
                        <img src="{{ public_path('storage/' . $book->gambar_sampul) }}" alt="Sampul">
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
