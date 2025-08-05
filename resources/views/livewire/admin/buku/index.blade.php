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
                            <a href="{{ route('admin.dashboard.index') }}">
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
                        <div class="col-5">
                            <input wire:model.live="search" type="text" class="form-control" placeholder="Pencarian...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ISBN</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun Terbit</th>
                                    <th>Kategori</th>
                                    <th>Rak</th>
                                    <th>Stok</th>
                                    <th>Tersedia</th>
                                    <th>Sampul</th>
                                    <th><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->isbn ?? '-' }}</td>
                                        <td>{{ $item->judul }}</td>
                                        <td>{{ $item->penulis }}</td>
                                        <td>{{ $item->penerbit }}</td>
                                        <td>{{ $item->tahun_terbit }}</td>
                                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td> {{-- Akses nama kategori --}}
                                        <td>{{ $item->rack->nama_rak ?? '-' }}</td> {{-- Akses nama rak --}}
                                        <td><span class="badge badge-info">{{ $item->jumlah_stok }}</span></td>
                                        <td><span class="badge badge-success">{{ $item->jumlah_tersedia }}</span></td>
                                        <td>
                                            @if ($item->gambar_sampul)
                                                <img src="{{ asset('storage/' . $item->gambar_sampul) }}" alt="Sampul" style="width: 50px; height: 75px; object-fit: cover;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning mb-2" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
                                            <button wire:click="confirm({{ $item->id }})" class="btn btn-sm btn-danger mb-2" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->

        {{-- Create Modal --}}
        @include('livewire.admin.buku.create')
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
        @include('livewire.admin.buku.edit')
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
        @include('livewire.admin.buku.delete')
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
