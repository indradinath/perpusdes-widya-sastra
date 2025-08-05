<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Kunjungan</title>
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
                <th>Nama Pengunjung</th>
                <th>Keperluan</th>
                <th>Asal Instansi</th>
                <th>Waktu Kunjungan</th>
                <th>Anggota Terkait</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visits as $key => $visit)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $visit->nama_pengunjung }}</td>
                <td>{{ $visit->keperluan }}</td>
                <td>{{ $visit->asal_instansi ?? '-' }}</td>
                <td>{{ $visit->waktu_kunjungan->format('d M Y H:i') }}</td>
                <td>{{ $visit->user->nama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
