<?php

namespace App\Livewire\Anggota\Kunjungan;

use App\Models\Visit;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';
    public $title = 'Riwayat Kunjungan';

    public function render()
    {
        $userId = Auth::id();

        $visits = Visit::query()
            ->where('user_id', $userId)
            ->orderBy('waktu_kunjungan', 'desc') 
            ->paginate($this->paginate);

        return view('livewire.anggota.kunjungan.index', [
            'visits' => $visits,
            'title' => $this->title,
        ]);
    }
}
