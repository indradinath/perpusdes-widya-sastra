<!DOCTYPE html>
    <html>
    <head>
        <title>Laporan Data Transaksi</title>
        <style>
            body { font-family: sans-serif; font-size: 8pt; } /* Ukuran font lebih kecil */
            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
            th, td { border: 1px solid #000; padding: 3px; text-align: left; }
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
                    <th>Kode Transaksi</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl Kembali</th>
                    <th>Denda (Rp)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $key => $transaction)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $transaction->kode_transaksi }}</td>
                    <td>{{ $transaction->user->nama ?? '-' }} ({{ $transaction->user->kode_anggota ?? '-' }})</td>
                    <td>{{ $transaction->book->judul ?? '-' }}</td>
                    <td>{{ $transaction->tanggal_peminjaman->format('d M Y') }}</td>
                    <td>{{ $transaction->tanggal_jatuh_tempo->format('d M Y') }}</td>
                    <td>{{ $transaction->tanggal_pengembalian?->format('d M Y') ?? '-' }}</td>
                    <td>{{ number_format($transaction->denda, 0, ',', '.') }}</td>
                    <td>{{ $transaction->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
    </html>
