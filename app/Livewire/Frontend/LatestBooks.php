<?php

namespace App\Livewire\Frontend;

use App\Models\Book;
use Livewire\Component;

class LatestBooks extends Component
{

    public $latestBooks;

    public function mount()
    {
        $this->latestBooks = Book::where('jumlah_tersedia', '>', 0)
                                ->orderBy('created_at', 'desc')
                                ->take(6)
                                ->get();
    }
    public function render()
    {

        return view('livewire.frontend.latest-books');
    }
}
