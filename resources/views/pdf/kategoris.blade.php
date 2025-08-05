<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Kategori Buku</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Laporan {{ $title }} Perpustakaan Desa Dauh Peken</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Tanggal Dibuat</th>
                <th>Tanggal Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoris as $key => $kategori)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $kategori->nama_kategori }}</td>
                <td>{{ $kategori->created_at->format('d M Y H:i') }}</td>
                <td>{{ $kategori->updated_at->format('d M Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
