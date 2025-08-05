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

        <div class="modal-body text-center">
            <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
            <p>Kode Transaksi: <strong>{{ $kode_transaksi }}</strong></p>
            <p>Anggota: <strong>{{ $selected_user_name }}</strong></p>
            <p>Buku: <strong>{{ $selected_book_title }}</strong></p>
            <p class="text-danger mt-2">Tindakan ini tidak dapat dibatalkan dan akan mengembalikan stok buku jika statusnya masih 'Dipinjam' atau 'Terlambat'.</p>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i>
                Tutup
            </button>
            <button wire:click="destroy({{ $transaction_id }})" type="button" class="btn btn-danger btn-sm">
                <i class="fas fa-trash mr-1"></i>
                Hapus
            </button>
        </div>
      </div>
    </div>
  </div>
