<?php

namespace App\Exports;

use App\Models\Visit;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VisitsExport implements FromCollection, WithHeadings, WithMapping
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
        return Visit::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where('nama_pengunjung', 'like', '%' . $this->search . '%')
                      ->orWhere('keperluan', 'like', '%' . $this->search . '%')
                      ->orWhere('asal_instansi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('waktu_kunjungan', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pengunjung',
            'Keperluan',
            'Asal Instansi',
            'Waktu Kunjungan',
            'Anggota Terkait',
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->id, // Atau gunakan $loop->iteration jika Anda memprosesnya di controller/Livewire
            $visit->nama_pengunjung,
            $visit->keperluan,
            $visit->asal_instansi ?? '-',
            $visit->waktu_kunjungan->format('d M Y H:i'),
            $visit->user->nama ?? '-',
        ];
    }
}
