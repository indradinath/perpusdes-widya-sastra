<div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-tachometer-alt mr-1"></i>
                            {{ $title }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <h5 class="mb-3">Statistik Umum</h5>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $totalAnggota }}</h3>
                                <p>Anggota Terdaftar</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="{{ route('admin.user.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totalJudulBuku }}</h3>
                                <p>Total Judul Buku</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <a href="{{ route('admin.buku.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalPeminjamanTransaksi }}</h3>
                                <p>Total Transaksi Peminjaman</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <a href="{{ route('admin.transaksi.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $totalAdmin }}</h3>
                                <p>Admin Terdaftar</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <a href="{{ route('admin.user.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Statistik Inventaris Buku</h5>
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-gradient-navy">
                            <div class="inner">
                                <h3>{{ $totalInventaris }}</h3>
                                <p>Total Inventaris Buku (di Rak)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <a href="{{ route('admin.buku.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-gradient-primary">
                            <div class="inner">
                                <h3>{{ $jumlahBukuDipinjam }}</h3>
                                <p>Jumlah Buku Dipinjam</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <a href="{{ route('admin.transaksi.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-gradient-success">
                            <div class="inner">
                                <h3>{{ $jumlahSisaBuku }}</h3>
                                <p>Sisa Buku Tersedia di Rak</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <a href="{{ route('admin.buku.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Informasi Lainnya</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-secondary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Anggota Baru Terdaftar</h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="users-list clearfix">
                                    @forelse($latestMembers as $member)
                                        <li>
                                            <img src="{{ asset('adminlte3/dist/img/user_anggota.png') }}" alt="User Image"> {{-- Ganti dengan gambar default user --}}
                                            <a class="users-list-name" href="#">{{ $member->nama }}</a>
                                            <span class="users-list-date">{{ $member->created_at->diffForHumans() }}</span>
                                        </li>
                                    @empty
                                        <li>Tidak ada anggota baru.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('admin.user.index') }}" class="uppercase">Lihat Semua Anggota</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Manajemen Hero Banner</h3>
                            </div>
                            <div class="card-body text-center">
                                <h4>Total Banner Aktif: <span class="badge badge-primary">{{ $totalActiveBanners }}</span></h4>
                                <p>Kelola tampilan utama website Anda.</p>
                                <a href="{{ route('admin.banner.index') }}" class="btn btn-info btn-sm">Kelola Banner <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
