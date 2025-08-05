<?php

namespace App\Livewire\Anggota\Buku;

use App\Models\Book;
use App\Models\Kategori;
use App\Models\Rack;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';
    public $title = 'Katalog Buku';

    public $detailBook;
    public $showDetailModal = false;

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
                        $q->where('nama_rak', 'like', '%' . $this->search . '%');
                    });
            })
            ->where('jumlah_tersedia', '>', 0)
            ->orderBy('judul', 'asc')
            ->paginate($this->paginate);

        return view('livewire.anggota.buku.index',[
            'books' => $books,
            'title' => $this->title,
        ]);
    }

    public function showDetail($id)
    {
        $this->detailBook = Book::with(['kategori', 'rack'])->findOrFail($id);
        $this->showDetailModal = true;
        $this->dispatch('showDetailModal');
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailBook = null;
        $this->dispatch('closeDetailModal');
    }
}
