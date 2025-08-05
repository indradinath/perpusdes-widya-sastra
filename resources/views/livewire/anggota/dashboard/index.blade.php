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
                            <li class="breadcrumb-item">
                                <a href="{{ route('anggota.dashboard.index') }}">
                                    <i class="nav-icon fas fa-home mr-1"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-tachometer-alt mr-1"></i>
                                {{ $title }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    {{-- Box Info: Buku Sedang Dipinjam --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $totalBukuDipinjam }}</h3>
                                <p>Buku Sedang Dipinjam</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <a href="{{ route('anggota.transaksi.index') }}" class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Box Info: Total Kunjungan --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totalKunjungan }}</h3>
                                <p>Total Kunjungan Anda</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-walking"></i>
                            </div>
                            <a href="{{ route('anggota.kunjungan.index') }}" class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Bagian untuk Buku Jatuh Tempo Mendekat --}}
                @if ($bukuJatuhTempoMendekat->count() > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-warning card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Buku Akan Jatuh Tempo
                                        @php
                                            $today = \Carbon\Carbon::now()->startOfDay();
                                            $minDaysToDue = $bukuJatuhTempoMendekat->map(function($item) use ($today) {
                                                $jatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();
                                                return $today->diffInDays($jatuhTempo, false);
                                            })->min();

                                            if ($minDaysToDue == 0) {
                                                echo "(Hari Ini!)";
                                            } elseif ($minDaysToDue > 0 && $minDaysToDue <= 3) {
                                                echo "(Dalam " . $minDaysToDue . " hari)";
                                            } else {
                                                echo "(Jatuh Tempo Mendekat)";
                                            }
                                        @endphp
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Buku</th>
                                                <th>Tgl. Pinjam</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Sisa Hari</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bukuJatuhTempoMendekat as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->book->judul ?? '-' }}</td>
                                                    <td>{{ $item->tanggal_peminjaman->format('d M Y') }}</td>
                                                    <td>{{ $item->tanggal_jatuh_tempo->format('d M Y') }}</td>
                                                    <td>
                                                        @php
                                                            $today = \Carbon\Carbon::now()->startOfDay();
                                                            $jatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();

                                                            // Hitung selisih hari dengan mempertimbangkan arah
                                                            $daysToDue = $today->diffInDays($jatuhTempo, false); // true untuk absolute, false untuk sign

                                                            // Jika tanggal jatuh tempo adalah hari ini, maka sisa hari adalah 0
                                                            if ($jatuhTempo->isSameDay($today)) {
                                                                $daysRemainingText = "Hari Ini!";
                                                                $badgeClass = "badge-warning";
                                                            }
                                                            // Jika tanggal jatuh tempo di masa depan
                                                            elseif ($jatuhTempo->isFuture()) {
                                                                $daysRemainingText = $daysToDue . " hari lagi";
                                                                $badgeClass = "badge-warning";
                                                            }
                                                            // Jika tanggal jatuh tempo di masa lalu (harusnya ini tidak terjadi di section 'mendekat')
                                                            else {
                                                                $daysRemainingText = "Terlambat";
                                                                $badgeClass = "badge-danger";
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ $daysRemainingText }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Bagian untuk Buku Terlambat --}}
                @if ($bukuTerlambat->count() > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-danger card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-clock mr-1"></i>
                                        Buku Terlambat Dikembalikan
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Buku</th>
                                                <th>Tgl. Pinjam</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Denda (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bukuTerlambat as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->book->judul ?? '-' }}</td>
                                                    <td>{{ $item->tanggal_peminjaman->format('d M Y') }}</td>
                                                    <td>{{ $item->tanggal_jatuh_tempo->format('d M Y') }}</td>
                                                    <td>{{ number_format($item->denda, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </section>
    </div>
</div>
