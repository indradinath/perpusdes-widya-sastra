<div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-walking mr-1"></i>
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
                    <h3 class="card-title">Daftar Riwayat Kunjungan Anda</h3>
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
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Waktu Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visits as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->nama ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->waktu_kunjungan)->translatedFormat('d F Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada riwayat kunjungan yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $visits->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
