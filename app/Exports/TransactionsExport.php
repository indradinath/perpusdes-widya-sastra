<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $search;
        protected $filterStatus;

        public function __construct($search = null, $filterStatus = null)
        {
            $this->search = $search;
            $this->filterStatus = $filterStatus;
        }
    public function collection()
    {
        return Transaction::query()
            ->with(['user', 'book'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_anggota', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('book', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn', 'like', '%' . $this->search . '%');
                })
                ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
    }
    public function headings(): array
        {
            return [
                'ID Transaksi',
                'Kode Transaksi',
                'Kode Anggota',
                'Nama Anggota',
                'Judul Buku',
                'ISBN Buku',
                'Tanggal Peminjaman',
                'Tanggal Jatuh Tempo',
                'Tanggal Pengembalian',
                'Denda (Rp)',
                'Status',
            ];
        }

        public function map($transaction): array
        {
            return [
                $transaction->id,
                $transaction->kode_transaksi,
                $transaction->user->kode_anggota ?? '-',
                $transaction->user->nama ?? '-',
                $transaction->book->judul ?? '-',
                $transaction->book->isbn ?? '-',
                $transaction->tanggal_peminjaman->format('Y-m-d'),
                $transaction->tanggal_jatuh_tempo->format('Y-m-d'),
                $transaction->tanggal_pengembalian ? $transaction->tanggal_pengembalian->format('Y-m-d') : '-',
                $transaction->denda,
                $transaction->status,
            ];
        }
}
