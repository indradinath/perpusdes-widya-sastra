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
                <label for="kode_transaksi">Kode Transaksi</label>
                <input wire:model="kode_transaksi" type="text" class="form-control" readonly> {{-- <-- Tambahkan ini --}}
            </div>
            <div class="form-group">
                <label for="user_id">Anggota Peminjam <span class="text-danger">*</span></label>
                <select wire:model="user_id" class="form-control @error('user_id') is-invalid @enderror">
                    <option value="">-- Pilih Anggota --</option>
                    @forelse($users as $user) {{-- Menggunakan @forelse untuk keamanan --}}
                        <option value="{{ $user->id }}">{{ $user->nama }} ({{ $user->kode_anggota }})</option>
                    @empty
                        <option value="">Tidak ada anggota tersedia</option>
                    @endforelse
                </select>
                @error('user_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="book_id">Buku yang Dipinjam <span class="text-danger">*</span></label>
                <select wire:model="book_id" class="form-control @error('book_id') is-invalid @enderror">
                    <option value="">-- Pilih Buku --</option>
                    @forelse($books as $book) {{-- Menggunakan @forelse untuk keamanan --}}
                        <option value="{{ $book->id }}">{{ $book->judul }} (Stok Tersedia: {{ $book->jumlah_tersedia }})</option>
                    @empty
                        <option value="">Tidak ada buku tersedia</option>
                    @endforelse
                </select>
                @error('book_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="tanggal_peminjaman">Tanggal Peminjaman <span class="text-danger">*</span></label>
                <input wire:model="tanggal_peminjaman" type="date" class="form-control @error('tanggal_peminjaman') is-invalid @enderror">
                @error('tanggal_peminjaman')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                <input wire:model="tanggal_jatuh_tempo" type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror">
                @error('tanggal_jatuh_tempo')<small class="text-danger">{{ $message }}</small>@enderror
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
