<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Pengguna</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>Laporan {{ $title }} Perpustakaan Desa Dauh Peken</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Anggota</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tgl Lahir</th>
                <th>Tempat Lahir</th>
                <th>JK</th>
                <th>No HP</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $user->kode_anggota ?? '-' }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '-' }}</td>
                <td>{{ $user->tempat_lahir ?? '-' }}</td>
                <td>{{ $user->jenis_kelamin ? substr($user->jenis_kelamin, 0, 1) : '-' }}</td>
                <td>{{ $user->no_hp ? '+62' . $user->no_hp : '-' }}</td>
                <td>{{ $user->alamat ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
