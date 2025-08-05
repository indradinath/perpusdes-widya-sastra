<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\User;
use App\Models\Book; 

class LibraryStats extends Component
{
    public $totalAnggotaTerdaftar;
    public $totalKoleksiBuku;

    public function mount()
    {
        $this->totalAnggotaTerdaftar = User::where('role', 'Anggota')->count();
        $this->totalKoleksiBuku = Book::count();
    }

    public function render()
    {
        return view('livewire.frontend.library-stats');
    }
}
