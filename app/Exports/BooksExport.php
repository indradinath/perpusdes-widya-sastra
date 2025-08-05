<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class BooksExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return Book::query()
            ->with(['kategori', 'rack']) // Pastikan relasi diload
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
    }
    public function headings(): array
    {
        return [
            'ID',
            'ISBN',
            'Judul',
            'Penulis',
            'Penerbit',
            'Tahun Terbit',
            'Deskripsi',
            'Jumlah Stok',
            'Jumlah Tersedia',
            'Kategori',
            'Rak',
            'Kode Rak',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    public function map($book): array
    {
        return [
            $book->id,
            $book->isbn ?? '-',
            $book->judul,
            $book->penulis,
            $book->penerbit,
            $book->tahun_terbit,
            $book->deskripsi ?? '-',
            $book->jumlah_stok,
            $book->jumlah_tersedia,
            $book->kategori->nama_kategori ?? '-',
            $book->rack->nama_rak ?? '-',
            // $book->rack->kode_rak ?? '-',
            $book->created_at->format('Y-m-d H:i:s'),
            $book->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
