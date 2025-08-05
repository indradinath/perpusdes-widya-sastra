<!-- Modal -->
<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <i class="fas fa-trash mr-1"></i>
            Hapus {{ $title }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-4">
                    Nama
                </div>
                <div class="col-8">
                    : {{ $nama }}
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    Email
                </div>
                <div class="col-8">
                    : {{ $email }}
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    Role
                </div>
                <div class="col-8">
                    :
                    @if ($role == 'Superadmin')
                        <span class="badge badge-danger">
                            {{ $role }}
                        </span>
                    @elseif ($role == 'Admin')
                        <span class="badge badge-warning">
                            {{ $role }}
                        </span>
                    @else
                        <span class="badge badge-primary">
                            {{ $role }}
                        </span>
                    @endif
                    {{-- @if ($role == 'Admin')
                        <span class="badge badge-info">
                            {{ $role }}
                        </span>
                    @else
                        <span class="badge badge-primary">
                            {{ $role }}
                        </span>
                    @endif --}}
                </div>
            </div>

            @if ($role == 'Anggota')
                <div class="row">
                    <div class="col-4">
                        Kode Anggota
                    </div>
                    <div class="col-8">
                        : {{ $kode_anggota ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        Tanggal Lahir
                    </div>
                    <div class="col-8">
                        : {{ optional($tanggal_lahir)->format('d M Y') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        Tempat Lahir
                    </div>
                    <div class="col-8">
                        : {{ $tempat_lahir ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        Jenis Kelamin
                    </div>
                    <div class="col-8">
                        : {{ $jenis_kelamin ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        No HP
                    </div>
                    <div class="col-8">
                        : {{ $no_hp ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        Alamat
                    </div>
                    <div class="col-8">
                        : {{ $alamat ?? '-' }}
                    </div>
                </div>
            @endif
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="destroy({{ $user_id }})" type="button" class="btn btn-danger btn-sm">
                <i class="fas fa-trash mr-1"></i>
                Hapus
            </button>
        </div>
      </div>
    </div>
  </div>
