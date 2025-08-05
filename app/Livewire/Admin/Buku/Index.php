<?php

namespace App\Livewire\Admin\Buku;

use App\Models\Book;
use App\Models\Rack;
use Livewire\Component;
use App\Models\Kategori;
use App\Exports\BooksExport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $paginate = 10;
    public $search = '';
    public $book_id;
    public $isbn, $judul, $penulis, $penerbit, $tahun_terbit, $deskripsi, $jumlah_stok;
    public $kategori_id, $rack_id;
    public $gambar_sampul;
    public $old_gambar_sampul;

    public $kategoris;
    public $racks;

    protected $listeners = ['deleteConfirmed' => 'destroy'];

    public function mount()
    {
        $this->kategoris = Kategori::all();
        $this->racks = Rack::all();
    }

    protected function rules()
    {
        $rules = [
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'required|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_stok'   => 'required|integer|min:0',
            'kategori_id'   => 'required|exists:kategoris,id',
            'rack_id'      => 'required|exists:racks,id',
            'deskripsi'     => 'nullable|string',
            'gambar_sampul' => 'nullable|image|max:2048',
        ];

        if ($this->book_id) {
            $rules['isbn'] = 'nullable|string|max:255|unique:books,isbn,' . $this->book_id;
        } else {
            $rules['isbn'] = 'nullable|string|max:255|unique:books,isbn';
        }

        return $rules;
    }

    protected $messages = [
        'judul.required' => 'Judul buku wajib diisi.',
        'penulis.required' => 'Nama penulis wajib diisi.',
        'penerbit.required' => 'Nama penerbit wajib diisi.',
        'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
        'tahun_terbit.min' => 'Tahun terbit tidak valid.',
        'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
        'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
        'jumlah_stok.min' => 'Jumlah stok tidak boleh kurang dari 0.',
        'kategori_id.required' => 'Kategori wajib dipilih.',
        'kategori_id.exists' => 'Kategori tidak valid.',
        'rack_id.required' => 'Rak buku wajib dipilih.',
        'rack_id.exists' => 'Rak buku tidak valid.',
        'isbn.unique' => 'ISBN sudah terdaftar.',
        'gambar_sampul.image' => 'File harus berupa gambar.',
        'gambar_sampul.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function render()
    {
        $books = Book::query()
            ->with(['kategori', 'rack'])
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('penulis', 'like', '%' . $this->search . '%')
                      ->orWhere('penerbit', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kategori', function ($q) {
                            $q->where('nama_kategori', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('rack', function ($q) {
                            $q->where('nama_rak', 'like', '%' . $this->search . '%')
                              ->orWhere('kode_rak', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('judul', 'asc')
            ->paginate($this->paginate);

        return view('livewire.admin.buku.index', [
            'books' => $books,
            'title' => 'Data Buku'
        ]);
    }

    public function create()
    {
        $this->reset([
            'isbn', 'judul', 'penulis', 'penerbit', 'tahun_terbit',
            'deskripsi', 'jumlah_stok', 'kategori_id', 'rack_id',
            'gambar_sampul', 'book_id'
        ]);
        $this->resetValidation();
        $this->dispatch('showCreateModal');
    }

    public function store()
    {
        $validatedData = $this->validate();

        if ($this->gambar_sampul) {
            $validatedData['gambar_sampul'] = $this->gambar_sampul->store('book-covers', 'public');
        }

        $validatedData['jumlah_tersedia'] = $validatedData['jumlah_stok'];

        Book::create($validatedData);

        $this->dispatch('closeCreateModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Buku Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $book = Book::with(['kategori', 'rack'])->findOrFail($id);
        $this->book_id = $book->id;
        $this->isbn = $book->isbn;
        $this->judul = $book->judul;
        $this->penulis = $book->penulis;
        $this->penerbit = $book->penerbit;
        $this->tahun_terbit = $book->tahun_terbit;
        $this->deskripsi = $book->deskripsi;
        $this->jumlah_stok = $book->jumlah_stok;
        $this->kategori_id = $book->kategori_id;
        $this->rack_id = $book->rack_id;
        $this->old_gambar_sampul = $book->gambar_sampul;
        $this->gambar_sampul = null;
        $this->dispatch('showEditModal');
    }

    public function update()
    {
        $validatedData = $this->validate();

        $book = Book::findOrFail($this->book_id);

        if ($this->gambar_sampul) {
            if ($this->old_gambar_sampul && Storage::disk('public')->exists($this->old_gambar_sampul)) {
                Storage::disk('public')->delete($this->old_gambar_sampul);
            }
            $validatedData['gambar_sampul'] = $this->gambar_sampul->store('book-covers', 'public');
        } else {
            $validatedData['gambar_sampul'] = $this->old_gambar_sampul;
        }

        if ($validatedData['jumlah_stok'] > $book->jumlah_stok) {
            $validatedData['jumlah_tersedia'] = $book->jumlah_tersedia + ($validatedData['jumlah_stok'] - $book->jumlah_stok);
        } else {
            $validatedData['jumlah_tersedia'] = $book->jumlah_tersedia;
        }


        $book->update($validatedData);

        $this->dispatch('closeEditModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Buku Berhasil Diperbarui']);
    }

    public function confirm($id)
    {
        $this->book_id = $id;
        $book = Book::findOrFail($id);
        $this->judul = $book->judul;
        $this->old_gambar_sampul = $book->gambar_sampul;
        $this->dispatch('showDeleteModal');
    }

    public function destroy()
    {
        $book = Book::findOrFail($this->book_id);

        if ($book->gambar_sampul && Storage::disk('public')->exists($book->gambar_sampul)) {
            Storage::disk('public')->delete($book->gambar_sampul);
        }

        $book->delete();

        $this->dispatch('closeDeleteModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Buku Berhasil Dihapus']);
    }

    public function exportExcel()
{
    return Excel::download(new BooksExport($this->search), 'data_buku.xlsx');
}

public function exportPdf()
{
    $books = Book::query()
        ->with(['kategori', 'rack'])
        ->when($this->search, function ($query) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%')
                  ->orWhere('penerbit', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kategori', function ($q) {
                        $q->where('nama_kategori', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('rack', function ($q) {
                        $q->where('nama_rak', 'like', '%' . $this->search . '%')
                          ->orWhere('kode_rak', 'like', '%' . $this->search . '%');
                  });
        })
        ->orderBy('judul', 'asc')
        ->get();

    $pdf = Pdf::loadView('pdf.books', ['books' => $books, 'title' => 'Data Buku']); 
    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'data_buku.pdf');
}
}
