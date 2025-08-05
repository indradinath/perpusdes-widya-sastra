<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-exchange-alt mr-1"></i>
                        {{ $title }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('anggota.dashboard.index') }}"> {{-- Atau rute dashboard anggota jika ada --}}
                                <i class="nav-icon fas fa-home mr-1"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <i class="fas fa-book mr-1"></i>
                            {{ $title }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Riwayat Transaksi Anda</h3>
            </div>

            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <div class="col-2">
                        <select wire:model.live="paginate" class="form-control">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-5">
                        {{-- Opsi: Filter Status --}}
                        <select wire:model.live="filter_status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                            <option value="Terlambat">Terlambat</option>
                        </select>
                    </div>
                    <div class="col-5">
                        {{-- Opsi: Input Pencarian --}}
                        <input wire:model.live="search" type="text" class="form-control" placeholder="Cari kode transaksi atau judul buku...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Judul Buku</th>
                                <th>Tgl. Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Tgl. Kembali</th>
                                <th>Denda (Rp)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_transaksi }}</td>
                                    <td>{{ $item->book->judul ?? '-' }}</td>
                                    <td>{{ $item->tanggal_peminjaman->format('d M Y') }}</td>
                                    <td>{{ $item->tanggal_jatuh_tempo->format('d M Y') }}</td>
                                    <td>{{ $item->tanggal_pengembalian?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ number_format($item->denda, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($item->status == 'Dipinjam')
                                            <span class="badge badge-info">{{ $item->status }}</span>
                                        @elseif ($item->status == 'Dikembalikan')
                                            <span class="badge badge-success">{{ $item->status }}</span>
                                        @elseif ($item->status == 'Terlambat')
                                            <span class="badge badge-danger">{{ $item->status }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada riwayat transaksi yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
