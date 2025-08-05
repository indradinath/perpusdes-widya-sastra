<?php

namespace App\Exports;

use App\Models\Rack;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RacksExport implements FromCollection, WithHeadings, WithMapping
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
        return Rack::query()
            ->when($this->search, function ($query) {
                $query->where('nama_rak', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_rak', 'asc')
            ->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Nama Rak',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    public function map($rack): array
    {
        return [
            $rack->id,
            $rack->nama_rak,
            $rack->created_at->format('Y-m-d H:i:s'),
            $rack->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
