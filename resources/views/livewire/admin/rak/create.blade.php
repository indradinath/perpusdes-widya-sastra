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
                <label for="nama_rak">Nama Rak <span class="text-danger">*</span></label>
                <input wire:model="nama_rak" type="text" class="form-control @error('nama_rak') is-invalid @enderror" placeholder="Masukkan Nama Rak">
                @error('nama_rak')<small class="text-danger">{{ $message }}</small>@enderror
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
