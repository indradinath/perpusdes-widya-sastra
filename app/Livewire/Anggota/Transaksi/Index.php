<?php

namespace App\Livewire\Anggota\Transaksi;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';
    public $filter_status = '';
    public $title = 'Riwayat Transaksi';


    public function render()
    {
        $userId = Auth::id();

        $transactions = Transaction::query()
        ->where('user_id', $userId)
        ->with(['book'])
        ->when($this->search, function ($query) {
            $query->where('kode_transaksi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('book', function ($q) {
                      $q->where('judul', 'like', '%' . $this->search . '%');
                  });
        })
        ->when($this->filter_status, function ($query) {
            $query->where('status', $this->filter_status);
        })
        ->orderBy('tanggal_peminjaman', 'desc')
        ->paginate($this->paginate);

        return view('livewire.anggota.transaksi.index', [
            'transactions' => $transactions,
            'title' => $this->title,
        ]);
    }
}
