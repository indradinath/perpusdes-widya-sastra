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
                    <label>Kode Transaksi</label>
                    <input type="text" class="form-control" value="{{ $kode_transaksi }}" readonly> 
                </div>
                <div class="form-group">
                    <label>Anggota Peminjam</label>
                    <input type="text" class="form-control" value="{{ $selected_user_name }}" readonly>
                </div>
                <div class="form-group">
                    <label>Buku yang Dipinjam</label>
                    <input type="text" class="form-control" value="{{ $selected_book_title }}" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggal_peminjaman">Tanggal Peminjaman <span class="text-danger">*</span></label>
                    <input wire:model.live="tanggal_peminjaman" type="date" class="form-control @error('tanggal_peminjaman') is-invalid @enderror"> {{-- wire:model.live --}}
                    @error('tanggal_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                    <input wire:model.live="tanggal_jatuh_tempo" type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror"> {{-- wire:model.live --}}
                    @error('tanggal_jatuh_tempo')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="tanggal_pengembalian">Tanggal Pengembalian (Isi untuk pengembalian)</label>
                    <input wire:model.live="tanggal_pengembalian" type="date" class="form-control @error('tanggal_pengembalian') is-invalid @enderror"> {{-- wire:model.live --}}
                    @error('tanggal_pengembalian')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                {{-- Tampilkan Denda yang Dihitung Otomatis (Read-only) --}}
                <div class="form-group">
                    <label>Denda Terhitung</label>
                    <input type="text" class="form-control" value="Rp. {{ number_format($calculated_denda, 0, ',', '.') }}" readonly>
                    <small class="text-info">Denda akan dihitung otomatis berdasarkan tanggal jatuh tempo dan tanggal pengembalian.</small>
                </div>
                {{-- Tampilkan Status Terkini (Read-only) --}}
                <div class="form-group">
                    <label>Status Transaksi</label>
                    <input type="text" class="form-control" value="{{ $current_status }}" readonly>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Tutup
                </button>
                <button wire:click="update({{ $transaction_id }})" type="button" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit mr-1"></i>
                    Update
                </button>
            </div>
        </div>
    </div>
</div>
