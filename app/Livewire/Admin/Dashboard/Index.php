<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Hero_Banner;
use Carbon\Carbon;

class Index extends Component
{
    public $title = 'Dashboard';

    // Statistik Umum
    public $totalAnggota;
    public $totalJudulBuku;
    public $totalAdmin;
    public $totalPeminjamanTransaksi;

    // Statistik Buku
    public $totalInventaris;
    public $jumlahBukuDipinjam;
    public $jumlahSisaBuku;

    // Statistik Hero Banner
    public $totalActiveBanners;

    // Opsional: Buku terbaru, Anggota baru terdaftar
    public $latestBooks; // Buku terbaru
    public $latestMembers; // Anggota baru terdaftar

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->totalAnggota = User::where('role', 'Anggota')->count();
        $this->totalJudulBuku = Book::count();
        $this->totalAdmin = User::where('role', 'Admin')->count();
        $this->totalPeminjamanTransaksi = Transaction::count();

        // Statistik Buku
        $this->totalInventaris = Book::sum('jumlah_stok');
        $this->jumlahBukuDipinjam = Book::sum('jumlah_stok') - Book::sum('jumlah_tersedia');
        $this->jumlahSisaBuku = Book::sum('jumlah_tersedia');

        // Statistik Hero Banner
        $this->totalActiveBanners = Hero_Banner::where('is_active', true)->count();

        // Opsional: Buku terbaru, Anggota baru terdaftar
        $this->latestBooks = Book::orderBy('created_at', 'desc')->take(5)->get();
        $this->latestMembers = User::where('role', 'Anggota')->orderBy('created_at', 'desc')->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index', [
            'title' => $this->title
        ]);
    }
}
