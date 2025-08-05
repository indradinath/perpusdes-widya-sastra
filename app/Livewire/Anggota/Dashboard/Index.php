<?php

namespace App\Livewire\Anggota\Dashboard;

use App\Models\Visit;
use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    public $title = 'Dashboard Anggota';
    public $totalBukuDipinjam = 0;
    public $totalKunjungan = 0;
    public $bukuJatuhTempoMendekat = [];
    public $bukuTerlambat = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function render()
    {
        return view('livewire.anggota.dashboard.index', [
            'title' => $this->title,
        ]);
    }

    private function loadDashboardData()
    {
        $userId = Auth::id();
        $today = Carbon::now()->startOfDay();


        Transaction::where('user_id', $userId)
                    ->where('status', 'Dipinjam')
                    ->whereDate('tanggal_jatuh_tempo', '<', $today)
                    ->get()
                    ->each(function ($transaction) use ($today)
                    {

                        if ($transaction->status !== 'Terlambat') {
                            $transaction->status = 'Terlambat';
                        }

                        $jatuhTempo = Carbon::parse($transaction->tanggal_jatuh_tempo)->startOfDay();
                        $daysLate = $jatuhTempo->diffInDays($today);
                        $dendaPerHari = 1000;
                        $calculatedDenda = $daysLate * $dendaPerHari;

                        if ($transaction->denda !== $calculatedDenda) {
                            $transaction->denda = $calculatedDenda;
                        }
                        $transaction->save();

                    });

        $this->totalBukuDipinjam = Transaction::where('user_id', $userId)
                                                ->where('status', ['Dipinjam', 'Terlambat'])
                                                ->count();

        $this->totalKunjungan = Visit::where('user_id', $userId)
                                            ->count();

        $this->bukuJatuhTempoMendekat = Transaction::where('user_id', $userId)
                                                    ->whereIn('status', ['Dipinjam'])
                                                    ->whereDate('tanggal_jatuh_tempo', '>=', $today)
                                                    ->whereDate('tanggal_jatuh_tempo', '<=', $today->copy()->addDays(3))
                                                    ->with('book')
                                                    ->get();

        $this->bukuTerlambat = Transaction::where('user_id', $userId)
                                            ->where('status', 'Terlambat')
                                            ->with('book')
                                            ->get();
    }
}
