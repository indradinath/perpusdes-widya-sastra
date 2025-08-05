<div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-images mr-1"></i>
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
                            <i class="fas fa-images mr-1"></i>
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
                            <input wire:model.live="search" type="text" class="form-control" placeholder="Cari Judul atau Deskripsi...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Urutan</th>
                                    <th>Aktif</th>
                                    <th><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banners as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->gambar)
                                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Banner" style="width: 100px; height: 60px; object-fit: cover;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->judul ?? '-' }}</td>
                                        <td>{{ Str::limit($item->deskripsi, 50) ?? '-' }}</td>
                                        <td>{{ $item->urutan }}</td>
                                        <td>
                                            @if ($item->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
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
                        {{ $banners->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->

        {{-- Create Modal --}}
        @include('livewire.admin.banner.create')
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
        @include('livewire.admin.banner.edit')
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
        @include('livewire.admin.banner.delete')
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
