<div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-house-user mr-1"></i>
                        {{ $title }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href={{ route('admin.dashboard.index') }}>
                                <i class="nav-icon fas fa-home mr-1"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            <i class="fas fa-house-user mr-1"></i>
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
                    <div class="d-flex justify-content-between">
                        <div>
                            <button wire:click="create" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Data
                            </button>
                        </div>

                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-print mr-1"></i>
                                Cetak
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item text-success" wire:click="exportExcel">
                                    <i class="fas fa-file-excel mr-1"></i>
                                    Excel
                                </a>
                                <a class="dropdown-item text-danger" wire:click="exportPdf">
                                    <i class="fas fa-file-pdf mr-1"></i>
                                    PDF
                                </a>
                            </div>
                        </div>
                    </div>
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

                        <div class="form-group col-3">
                            <select wire:model.live="filter_status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="Dipinjam">Dipinjam</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>

                        <div class="col-5">
                            <input wire:model.live="search" type="text" class="form-control" placeholder="Pencarian...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tgl. Pinjam</th>
                                    <th>Tgl. Jatuh Tempo</th>
                                    <th>Tgl. Kembali</th>
                                    <th>Status</th>
                                    <th>Denda</th>
                                    <th>
                                        <i class="fas fa-cog"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $item)
                                    <tr>
                                        <td>{{ $transactions->firstItem() + $index }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->user->nama ?? 'N/A' }} ({{ $item->user->kode_anggota ?? 'N/A' }})</td>
                                        <td>{{ $item->book->judul ?? 'N/A' }}</td>
                                        <td>{{ $item->tanggal_peminjaman?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $item->tanggal_jatuh_tempo?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $item->tanggal_pengembalian?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            @if($item->status == 'Dipinjam')
                                                <span class="badge badge-info">{{ $item->status }}</span>
                                            @elseif($item->status == 'Terlambat')
                                                <span class="badge badge-danger">{{ $item->status }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>Rp. {{ number_format($item->denda, 0, ',', '.') }}</td>
                                        <td>
                                            <button wire:click="edit({{ $item->id }})" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" title="Edit/Kembalikan">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirm({{ $item->id }})" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data transaksi ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->


        {{-- Create Modal --}}
        @include('livewire.admin.transaksi.create')
        {{-- Create Modal --}}

        {{-- Close Create Modal --}}
        @script
            <script>
                $wire.on('closeCreateModal', () => {
                    $('#createModal').modal('hide');
                    Swal.fire({
                        title: "Sukses",
                        text: "Data Berhasil Di Tambahkan",
                        icon: "success"
                    });
                });
            </script>
        @endscript
        {{-- Close Create Modal --}}

        {{-- Edit Modal --}}
        @include('livewire.admin.transaksi.edit')
        {{-- Edit Modal --}}

        {{-- Close Edit Modal --}}
        @script
            <script>
                $wire.on('closeEditModal', () => {
                    $('#editModal').modal('hide');
                    Swal.fire({
                        title: "Sukses",
                        text: "Data Berhasil Di Perbarui",
                        icon: "success"
                    });
                });
            </script>
        @endscript
        {{-- Close Edit Modal --}}

        {{-- Delete Modal --}}
        @include('livewire.admin.transaksi.delete')
        {{-- Delete Modal --}}

        {{-- Close Delete Modal --}}
        @script
            <script>
                $wire.on('closeDeleteModal', () => {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: "Sukses",
                        text: "Data Berhasil Di Hapus",
                        icon: "success"
                    });
                });
            </script>
        @endscript
        {{-- Close Delete Modal --}}

    </div>
</div>
