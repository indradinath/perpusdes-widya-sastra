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
            <div class="modal-body">
                <p>Anda yakin ingin menghapus rak: <strong>{{ $nama_rak }}</strong>?</p>
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="destroy({{ $rack_id }})" type="button" class="btn btn-danger btn-sm">
                <i class="fas fa-trash mr-1"></i>
                Hapus
            </button>
        </div>
      </div>
    </div>
  </div>
