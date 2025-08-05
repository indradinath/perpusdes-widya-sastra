<?php

namespace App\Exports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class KategorisExport implements FromCollection, WithHeadings, WithMapping
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
        return Kategori::query()
            ->when($this->search, function ($query) {
                $query->where('nama_kategori', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_kategori', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kategori',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    public function map($kategori): array
    {
        return [
            $kategori->id,
            $kategori->nama_kategori,
            $kategori->created_at->format('Y-m-d H:i:s'),
            $kategori->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
