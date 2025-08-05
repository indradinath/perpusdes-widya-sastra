<div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-user-circle mr-1"></i>
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
                                <i class="fas fa-user-circle mr-1"></i>
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
                    <div class="col-md-6">
                        {{-- Form Update Informasi Profil --}}
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Profil</h3>
                            </div>
                            <form wire:submit.prevent="updateProfileInformation">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap</label>
                                        <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Masukkan nama lengkap">
                                        @error('nama') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Masukkan email">
                                        @error('email') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_anggota">Kode Anggota</label>
                                        <input wire:model="kode_anggota" type="text" class="form-control" id="kode_anggota" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input wire:model="tanggal_lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir">
                                        @error('tanggal_lahir') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input wire:model="tempat_lahir" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" placeholder="Masukkan tempat lahir">
                                        @error('tempat_lahir') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select wire:model="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp">Nomor HP</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+62</span>
                                            </div>
                                            <input wire:model="no_hp" type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" placeholder="Masukkan nomor HP">
                                        </div>
                                        @error('no_hp') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                        @error('alamat') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Form Update Password --}}
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Perbarui Password</h3>
                            </div>
                            <form wire:submit.prevent="updatePassword">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="current_password">Password Saat Ini</label>
                                        <input wire:model="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" placeholder="Masukkan password saat ini">
                                        @error('current_password') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Masukkan password baru">
                                        @error('password') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                                        <input wire:model="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Konfirmasi password baru">
                                        @error('password_confirmation') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-warning">Ubah Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
