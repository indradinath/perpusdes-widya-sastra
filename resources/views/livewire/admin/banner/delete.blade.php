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
            <p>Anda yakin ingin menghapus banner ini?</p>
            <p><strong>Judul:</strong> {{ $judul ?? '-' }}</p>
            @if($old_gambar)
                <div class="mt-2">
                    <p>Gambar:</p>
                    <img src="{{ asset('storage/' . $old_gambar) }}" style="max-width: 150px; height: auto;">
                </div>
            @endif
            <p class="text-danger mt-2">Tindakan ini juga akan menghapus gambar banner dan tidak dapat dibatalkan.</p>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="destroy({{ $banner_id }})" type="button" class="btn btn-danger btn-sm">
                <i class="fas fa-trash mr-1"></i>
                Hapus
            </button>
        </div>
      </div>
    </div>
  </div>
