<div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-book mr-1"></i>
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
                    <h3 class="card-title">Katalog Buku Tersedia</h3>
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
                            <input wire:model.live="search" type="text" class="form-control" placeholder="Cari judul, penulis, penerbit, ISBN, kategori, atau rak...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun</th>
                                    <th>Kategori</th>
                                    <th>Rak</th>
                                    <th>Tersedia</th>
                                    <th style="width: 80px; text-align: center;">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($books as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->gambar_sampul)
                                                <img src="{{ asset('public/storage/' . $item->gambar_sampul) }}" alt="Sampul" style="width: 50px; height: auto;">
                                            @else
                                                <i class="fas fa-image text-muted" style="font-size: 30px;"></i>
                                            @endif
                                        </td>
                                        <td>{{ $item->judul }}</td>
                                        <td>{{ $item->penulis }}</td>
                                        <td>{{ $item->penerbit }}</td>
                                        <td>{{ $item->tahun_terbit }}</td>
                                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                        <td>{{ $item->rack->nama_rak ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-success">{{ $item->jumlah_tersedia }}</span>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-sm btn-info" wire:click="showDetail({{ $item->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- TODO: Buat modal detail buku di sini jika diperlukan --}}
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data buku yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div wire:ignore.self class="modal fade" id="detailBookModal" tabindex="-1" role="dialog" aria-labelledby="detailBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailBookModalLabel">
                        <i class="fas fa-info-circle mr-1"></i> Detail Buku
                    </h5>
                    <button type="button" class="close" wire:click="closeDetailModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Pastikan detailBook tidak null sebelum ditampilkan untuk menghindari error --}}
                    @if ($detailBook)
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                @if ($detailBook->gambar_sampul)
                                    <img src="{{ asset('public/storage/' . $detailBook->gambar_sampul) }}" alt="Sampul Buku" class="img-fluid rounded" style="max-height: 250px;">
                                @else
                                    <i class="fas fa-book-open text-muted" style="font-size: 150px;"></i>
                                    <p class="text-muted mt-2">Tidak ada sampul</p>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4 class="text-primary">{{ $detailBook->judul }}</h4>
                                <p class="text-muted small">ISBN: {{ $detailBook->isbn ?? '-' }}</p>
                                <hr>
                                <p><strong>Penulis:</strong> {{ $detailBook->penulis }}</p>
                                <p><strong>Penerbit:</strong> {{ $detailBook->penerbit }}</p>
                                <p><strong>Tahun Terbit:</strong> {{ $detailBook->tahun_terbit }}</p>
                                <p><strong>Kategori:</strong> {{ $detailBook->kategori->nama_kategori ?? '-' }}</p>
                                <p><strong>Rak:</strong> {{ $detailBook->rack->nama_rak ?? '-' }}</p>
                                <p>
                                    <strong>Stok Tersedia:</strong>
                                    <span class="badge badge-success">{{ $detailBook->jumlah_tersedia }}</span> dari
                                    <span class="badge badge-info">{{ $detailBook->jumlah_stok }}</span> total
                                </p>
                                <p><strong>Deskripsi:</strong></p>
                                <p class="text-justify">{{ $detailBook->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-center">Memuat detail buku...</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            // Event listener untuk membuka modal detail
            $wire.on('showDetailModal', () => {
                $('#detailBookModal').modal('show');
            });

            // Event listener untuk menutup modal detail
            $wire.on('closeDetailModal', () => {
                $('#detailBookModal').modal('hide');
            });
        </script>
    @endscript
</div>

