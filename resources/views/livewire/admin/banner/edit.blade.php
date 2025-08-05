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

            <div class="form-group">
                <label for="gambar">Gambar Banner (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" wire:model="gambar" class="form-control-file @error('gambar') is-invalid @enderror">
                @error('gambar')<small class="text-danger">{{ $message }}</small>@enderror

                @if ($gambar)
                    <div class="mt-2">
                        <p>Pratinjau Gambar Baru:</p>
                        <img src="{{ $gambar->temporaryUrl() }}" style="max-width: 200px; height: auto;">
                    </div>
                @elseif($old_gambar)
                    <div class="mt-2">
                        <p>Gambar Banner Saat Ini:</p>
                        <img src="{{ asset('storage/' . $old_gambar) }}" style="max-width: 200px; height: auto;">
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="judul">Judul (Opsional)</label>
                <input wire:model="judul" type="text" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan Judul Banner">
                @error('judul')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi (Opsional)</label>
                <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Masukkan Deskripsi Banner"></textarea>
                @error('deskripsi')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="urutan">Urutan Tampil (Opsional)</label>
                <input wire:model="urutan" type="number" min="0" class="form-control @error('urutan') is-invalid @enderror" placeholder="Masukkan angka urutan">
                @error('urutan')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group form-check">
                <input type="checkbox" wire:model="is_active" class="form-check-input" id="is_active_edit">
                <label class="form-check-label" for="is_active_edit">Aktif</label>
                @error('is_active')<small class="text-danger d-block">{{ $message }}</small>@enderror
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="update({{ $banner_id }})" type="button" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i>
                Update
            </button>
        </div>
      </div>
    </div>
  </div>
