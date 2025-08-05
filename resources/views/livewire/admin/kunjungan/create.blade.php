<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <i class="fas fa-plus mr-1"></i>
            Tambah {{ $title }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label for="nama_pengunjung">Nama Pengunjung <span class="text-danger">*</span></label>
                <input wire:model="nama_pengunjung" type="text" class="form-control @error('nama_pengunjung') is-invalid @enderror" placeholder="Masukkan Nama Pengunjung">
                @error('nama_pengunjung')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="keperluan">Keperluan Kunjungan <span class="text-danger">*</span></label>
                <input wire:model="keperluan" type="text" class="form-control @error('keperluan') is-invalid @enderror" placeholder="Contoh: Membaca buku, Peminjaman">
                @error('keperluan')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="asal_instansi">Asal Instansi (Opsional)</label>
                <input wire:model="asal_instansi" type="text" class="form-control @error('asal_instansi') is-invalid @enderror" placeholder="Contoh: SD Negeri 1 Dauh Peken">
                @error('asal_instansi')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="user_id">Anggota Terkait (Opsional)</label>
                <select wire:model="user_id" class="form-control @error('user_id') is-invalid @enderror">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->nama }} ({{ $user->kode_anggota }})</option>
                    @endforeach
                </select>
                @error('user_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="store" type="button" class="btn btn-primary btn-sm">
                <i class="fas fa-save mr-1"></i>
                Simpan
            </button>
        </div>
      </div>
    </div>
  </div>
