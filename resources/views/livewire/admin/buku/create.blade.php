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
                <label for="isbn">ISBN (Opsional)</label>
                <input wire:model="isbn" type="text" class="form-control @error('isbn') is-invalid @enderror" placeholder="Contoh: 978-0-321-76572-3">
                @error('isbn')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="judul">Judul Buku <span class="text-danger">*</span></label>
                <input wire:model="judul" type="text" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan Judul Buku">
                @error('judul')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="penulis">Penulis <span class="text-danger">*</span></label>
                <input wire:model="penulis" type="text" class="form-control @error('penulis') is-invalid @enderror" placeholder="Masukkan Nama Penulis">
                @error('penulis')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="penerbit">Penerbit <span class="text-danger">*</span></label>
                <input wire:model="penerbit" type="text" class="form-control @error('penerbit') is-invalid @enderror" placeholder="Masukkan Nama Penerbit">
                @error('penerbit')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit <span class="text-danger">*</span></label>
                <input wire:model="tahun_terbit" type="number" min="1900" max="{{ date('Y') }}" class="form-control @error('tahun_terbit') is-invalid @enderror" placeholder="Contoh: 2023">
                @error('tahun_terbit')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="jumlah_stok">Jumlah Stok <span class="text-danger">*</span></label>
                <input wire:model="jumlah_stok" type="number" min="0" class="form-control @error('jumlah_stok') is-invalid @enderror" placeholder="Masukkan Jumlah Stok">
                @error('jumlah_stok')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                <select wire:model="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('kategori_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="rack_id">Rak Buku <span class="text-danger">*</span></label>
                <select wire:model="rack_id" class="form-control @error('rack_id') is-invalid @enderror">
                    <option value="">-- Pilih Rak --</option>
                    @foreach($racks as $rack)
                        <option value="{{ $rack->id }}">{{ $rack->nama_rak }} ({{ $rack->kode_rak }})</option>
                    @endforeach
                </select>
                @error('rack_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi (Opsional)</label>
                <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Masukkan Deskripsi Buku"></textarea>
                @error('deskripsi')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="gambar_sampul">Gambar Sampul (Opsional)</label>
                <input type="file" wire:model="gambar_sampul" class="form-control-file @error('gambar_sampul') is-invalid @enderror">
                @error('gambar_sampul')<small class="text-danger">{{ $message }}</small>@enderror

                @if ($gambar_sampul)
                    <div class="mt-2">
                        <p>Pratinjau Gambar:</p>
                        <img src="{{ $gambar_sampul->temporaryUrl() }}" style="max-width: 150px; height: auto;">
                    </div>
                @endif
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
