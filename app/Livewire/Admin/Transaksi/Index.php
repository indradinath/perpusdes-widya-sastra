<?php

namespace App\Livewire\Admin\Transaksi;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    public $paginate = 10;
    public $search = '';
    public $filter_status = '';
    public $transaction_id;

    // Properti untuk form peminjaman
    public $user_id, $book_id, $tanggal_peminjaman, $tanggal_jatuh_tempo;
    public $selected_user_name, $selected_book_title;
    public $kode_transaksi;

    // Properti untuk denda yang akan dihitung dan ditampilkan (bukan diinput)
    public $calculated_denda = 0;
    public $current_status = '';

    public $users;
    public $books;
    public $tanggal_pengembalian;

    protected $listeners = ['deleteConfirmed' => 'destroy'];

    // Metode mount akan dijalankan sekali saat komponen diinisialisasi
    public function mount()
    {
        $this->loadDropdownData(); // Muat data untuk dropdown User dan Book
        $this->tanggal_peminjaman = Carbon::now()->format('Y-m-d'); // Default tanggal peminjaman hari ini
        $this->tanggal_jatuh_tempo = Carbon::now()->addDays(7)->format('Y-m-d'); // Default jatuh tempo 7 hari dari sekarang
    }

    // Metode untuk memuat data dropdown User (Anggota) dan Book (tersedia)
    private function loadDropdownData()
    {
        $this->users = User::where('role', 'Anggota')->orderBy('nama', 'asc')->get();
        $this->books = Book::where('jumlah_tersedia', '>', 0)->orderBy('judul', 'asc')->get();
    }

    // Aturan validasi untuk form
    protected function rules()
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_peminjaman',
        ];

        // Tambahan aturan untuk tanggal pengembalian saat update
        if ($this->transaction_id) {
            $rules['tanggal_pengembalian'] = 'nullable|date|after_or_equal:tanggal_peminjaman';
        }
        return $rules;
    }

    // Pesan kustom untuk validasi
    protected $messages = [
        'user_id.required' => 'Anggota peminjam wajib diisi.',
        'user_id.exists' => 'Anggota tidak valid.',
        'book_id.required' => 'Buku yang dipinjam wajib diisi.',
        'book_id.exists' => 'Buku tidak valid.',
        'tanggal_peminjaman.required' => 'Tanggal peminjaman wajib diisi.',
        'tanggal_peminjaman.date' => 'Format tanggal peminjaman tidak valid.',
        'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
        'tanggal_jatuh_tempo.date' => 'Format tanggal jatuh tempo tidak valid.',
        'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum tanggal peminjaman.',
        'tanggal_pengembalian.date' => 'Format tanggal pengembalian tidak valid.',
        'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',
    ];

    // Metode utama untuk merender tampilan
    public function render()
    {
        $this->loadDropdownData();
        $this->updateOverdueStatus();

        $transactions = Transaction::query()
            ->with(['user', 'book'])
            ->when($this->search, function ($query) {
                // Pencarian berdasarkan nama/kode anggota, judul/ISBN buku, atau status
                $query->whereHas('user', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_anggota', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
                })->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter_status, function ($query) {
                // Filter berdasarkan status
                $query->where('status', $this->filter_status);
            })
            ->orderBy('tanggal_peminjaman', 'desc')
            ->paginate($this->paginate);

        return view('livewire.admin.transaksi.index', [
            'transactions' => $transactions,
            'title' => 'Data Transaksi'
        ]);
    }

    // Metode untuk membuka modal tambah data
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'kode_transaksi', 'user_id', 'book_id', 'tanggal_peminjaman', 'tanggal_jatuh_tempo',
            'tanggal_pengembalian', 'transaction_id', 'selected_user_name',
            'selected_book_title', 'calculated_denda', 'current_status'
        ]);
        $this->tanggal_peminjaman = Carbon::now()->format('Y-m-d');
        $this->tanggal_jatuh_tempo = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->loadDropdownData();
        $this->kode_transaksi = $this->generateUniqueKodeTransaksi();
        $this->dispatch('showCreateModal');
    }

    private function generateUniqueKodeTransaksi()
{
    $prefix = 'TRX' . Carbon::now()->format('ymd');
    $lastTransaction = Transaction::latest('id')->first();
    $lastId = $lastTransaction ? $lastTransaction->id : 0;
    $newId = $lastId + 1;

    do {
        $code = $prefix . str_pad($newId, 4, '0', STR_PAD_LEFT);
        $exists = Transaction::where('kode_transaksi', $code)->exists();
        $newId++;
    } while ($exists);

    return $code;
}

    // Metode untuk menyimpan data transaksi baru (peminjaman)
    public function store()
    {
        $validatedData = $this->validate();

        DB::transaction(function () use ($validatedData) {
            $book = Book::find($validatedData['book_id']);
            if (!$book || $book->jumlah_tersedia < 1) {
                // Jika buku tidak ditemukan atau stok tidak mencukupi
                $this->addError('book_id', 'Stok buku tidak mencukupi.');
                return;
            }

            Transaction::create([
                'kode_transaksi' => $this->kode_transaksi,
                'user_id' => $validatedData['user_id'],
                'book_id' => $validatedData['book_id'],
                'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
                'tanggal_jatuh_tempo' => $validatedData['tanggal_jatuh_tempo'],
                'status' => 'Dipinjam',
                'denda' => 0,
            ]);

            $book->decrement('jumlah_tersedia');
        });

        $this->dispatch('closeCreateModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Peminjaman Berhasil Dicatat.']);
    }

    // Metode untuk membuka modal edit dan mengisi data
    public function edit($id)
    {
        $this->resetValidation();
        $transaction = Transaction::with(['user', 'book'])->findOrFail($id);
        $this->transaction_id = $transaction->id;
        $this->kode_transaksi = $transaction->kode_transaksi;
        $this->user_id = $transaction->user_id;
        $this->book_id = $transaction->book_id;
        $this->tanggal_peminjaman = $transaction->tanggal_peminjaman?->format('Y-m-d');
        $this->tanggal_jatuh_tempo = $transaction->tanggal_jatuh_tempo?->format('Y-m-d');
        $this->tanggal_pengembalian = $transaction->tanggal_pengembalian?->format('Y-m-d');

        $this->selected_user_name = $transaction->user->nama . ' (' . $transaction->user->kode_anggota . ')';
        $this->selected_book_title = $transaction->book->judul;

        $this->calculateFineAndStatus();

        $this->dispatch('showEditModal');
    }

    // Metode untuk menghitung denda dan menentukan status (dipanggil otomatis atau saat edit)
    public function calculateFineAndStatus()
    {
        $jatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo)->startOfDay();
        $pengembalian = $this->tanggal_pengembalian ? Carbon::parse($this->tanggal_pengembalian)->startOfDay() : null;
        $today = Carbon::now()->startOfDay();

        $dendaPerHari = 1000;

        // Reset denda dan status awal
        $this->calculated_denda = 0;
        $this->current_status = 'Dipinjam';

        if ($pengembalian) {
            // Jika buku sudah dikembalikan
            $this->current_status = 'Dikembalikan';
            if ($pengembalian->greaterThan($jatuhTempo)) {
                $diffInDays = $pengembalian->diffInDays($jatuhTempo);
                $this->calculated_denda = (int) ($diffInDays * $dendaPerHari);
            }
        } else {
            // Jika buku belum dikembalikan
            if ($today->greaterThan($jatuhTempo)) {
                $this->current_status = 'Terlambat';
                $diffInDays = $jatuhTempo->diffInDays($today);
                $this->calculated_denda = (int) ($diffInDays * $dendaPerHari);
            }
        }
        $this->calculated_denda = max(0, $this->calculated_denda);

    }

    // Metode yang dipanggil otomatis oleh Livewire saat properti tanggal berubah di form edit
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['tanggal_peminjaman', 'tanggal_jatuh_tempo', 'tanggal_pengembalian'])) {
            $this->validateOnly($propertyName);
            $this->calculateFineAndStatus();
        }
    }

    // Metode untuk memperbarui data transaksi (termasuk pengembalian)
    public function update()
    {
        $validatedData = $this->validate(); // Validasi input

        $transaction = Transaction::findOrFail($this->transaction_id);

        DB::transaction(function () use ($validatedData, $transaction) {
            $oldTanggalPengembalian = $transaction->tanggal_pengembalian;

            // Update properti transaksi
            $transaction->tanggal_peminjaman = $validatedData['tanggal_peminjaman'];
            $transaction->tanggal_jatuh_tempo = $validatedData['tanggal_jatuh_tempo'];
            $transaction->tanggal_pengembalian = $validatedData['tanggal_pengembalian'];

            // Panggil kembali calculateFineAndStatus untuk mengisi $this->calculated_denda dan $this->current_status
            $this->calculateFineAndStatus();

            // Set denda dan status dari properti yang sudah dihitung
            $transaction->denda = $this->calculated_denda;
            $transaction->status = $this->current_status;

            // Logika penyesuaian stok buku berdasarkan perubahan status pengembalian
            $book = Book::find($transaction->book_id);
            if ($book) {
                // Jika sebelumnya belum dikembalikan dan sekarang dikembalikan
                if (!$oldTanggalPengembalian && $transaction->tanggal_pengembalian) {
                    $book->increment('jumlah_tersedia');
                }
                // Jika sebelumnya sudah dikembalikan dan sekarang dibatalkan pengembaliannya
                elseif ($oldTanggalPengembalian && !$transaction->tanggal_pengembalian) {
                    if ($book->jumlah_tersedia > 0) {
                        $book->decrement('jumlah_tersedia');
                    }
                }
            }
            $transaction->save();
        });

        $this->dispatch('closeEditModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Transaksi Berhasil Diperbarui.']); // Notifikasi
    }

    // Metode untuk konfirmasi hapus transaksi
    public function confirm($id)
    {
        $this->transaction_id = $id;
        $transaction = Transaction::with(['user', 'book'])->findOrFail($id);
        $this->selected_user_name = $transaction->user->nama . ' (' . $transaction->user->kode_anggota . ')';
        $this->selected_book_title = $transaction->book->judul;
        $this->dispatch('confirmDelete');
    }

    // Metode untuk menghapus transaksi (dipanggil setelah konfirmasi SweetAlert)
    public function destroy()
    {
        $transaction = Transaction::findOrFail($this->transaction_id);

        DB::transaction(function () use ($transaction) {
            if ($transaction->status != 'Dikembalikan') {
                $book = Book::find($transaction->book_id);
                if ($book) {
                    $book->increment('jumlah_tersedia');
                }
            }
            $transaction->delete();
        });

        $this->dispatch('closeDeleteModal'); 
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Transaksi Berhasil Dihapus.']);
    }

    // Metode untuk memperbarui status transaksi yang terlambat secara otomatis
    public function updateOverdueStatus()
    {
        Transaction::where('status', 'Dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::now()->toDateString())
            ->get()
            ->each(function ($transaction)
            {
                $transaction->status = 'Terlambat';
                $today = Carbon::now()->startOfDay();
                $jatuhTempo = $transaction->tanggal_jatuh_tempo->startOfDay();

                $diffInDays = $jatuhTempo->diffInDays($today);
                $transaction->denda = (int) (max(0, $diffInDays) * 1000);
                $transaction->save();

            });
    }

    // Metode untuk ekspor data ke Excel
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport($this->search, $this->filter_status), 'data_transaksi.xlsx');
    }

    // Metode untuk ekspor data ke PDF
    public function exportPdf()
    {
        $transactions = Transaction::query()
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
            ->when($this->filter_status, function ($query) {
                $query->where('status', $this->filter_status);
            })
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.transactions', ['transactions' => $transactions, 'title' => 'Data Transaksi']);
        $pdf->setPaper('A4', 'landscape');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'data_transaksi.pdf');
    }
}
