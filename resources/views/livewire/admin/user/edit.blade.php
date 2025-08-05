<!-- Modal -->
<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <i class="fas fa-edit mr-1"></i>
            Edit {{ $title }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="row mt-2">
                <label for="nama" class="form-label">
                    Nama
                </label>
                <span class="text-danger">*</span>
                <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                @error('nama')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="row mt-2">
                <label for="email" class="form-label">
                    Email
                </label>
                <span class="text-danger">*</span>
                <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
                @error('email')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="row mt-2">
                <label for="role" class="form-label">
                    Role
                </label>
                <span class="text-danger">*</span>
                <select id="role" wire:model="role" class="form-control @error('role') is-invalid @enderror">
                    <option selected>--Pilih Role--</option>
                    {{-- <option value="Admin">Admin</option> --}}
                    <option value="Anggota">Anggota</option>
                    @if(Auth::user()->role === 'Superadmin')
                        <option value="Admin">Admin</option>
                        <option value="Superadmin">Superadmin</option>
                    @endif
                </select>
                @error('role')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            @if ($role == 'Anggota')
                <div class="row mt-2">
                    <label for="kode_anggota" class="form-label">Kode Anggota</label>
                    <input wire:model="kode_anggota" type="text" class="form-control" readonly>
                </div>

                <div class="row mt-2">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <span class="text-danger">*</span>
                    <input wire:model="tanggal_lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror">
                    @error('tanggal_lahir')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="row mt-2">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <span class="text-danger">*</span>
                    <input wire:model="tempat_lahir" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" placeholder="Masukkan Tempat Lahir">
                    @error('tempat_lahir')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="row mt-2">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <span class="text-danger">*</span>
                    <select wire:model="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                        <option selected>--Pilih Jenis Kelamin--</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="row mt-2">
                    <label for="no_hp" class="form-label">No HP</label>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+62</span>
                        </div>
                        <input wire:model="no_hp" type="text" class="form-control @error('no_hp') is-invalid @enderror" placeholder="Masukkan Nomor HP">
                    </div>
                    @error('no_hp')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="row mt-2">
                    <label for="alamat" class="form-label">Alamat</label>
                    <span class="text-danger">*</span>
                    <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan Alamat Lengkap"></textarea>
                    @error('alamat')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
            @endif

            <div class="row mt-2">
                <label for="password" class="form-label">Password</label>
                <span class="text-danger">*</span>
                <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
                @error('password')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="row mt-2">
                <label for="password_confirmation" class="form-label">Password Konfirmasi</label>
                <span class="text-danger">*</span>
                <input wire:model="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Masukkan Password Konfirmasi">
                @error('password_confirmation')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="update({{ $user_id }})" type="button" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i>
                Update
            </button>
        </div>
      </div>
    </div>
  </div>
