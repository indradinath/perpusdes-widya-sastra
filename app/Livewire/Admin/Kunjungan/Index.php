<?php

namespace App\Livewire\Admin\Kunjungan;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visit;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\VisitsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    public $paginate = 10;
    public $search = '';
    public $visit_id;
    public $nama_pengunjung, $keperluan, $asal_instansi, $user_id;
    public $title = 'Data Kunjungan';

    protected $listeners = ['deleteConfirmed' => 'destroy'];

    protected function rules()
    {
        return [
            'nama_pengunjung' => 'required|string|max:255',
            'keperluan' => 'required|string|max:255',
            'asal_instansi' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    protected $messages = [
        'nama_pengunjung.required' => 'Nama pengunjung wajib diisi.',
        'keperluan.required' => 'Keperluan kunjungan wajib diisi.',
        'user_id.exists' => 'Anggota tidak ditemukan.',
    ];


    public function render()
    {
        $visits = Visit::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where('nama_pengunjung', 'like', '%' . $this->search . '%')
                      ->orWhere('keperluan', 'like', '%' . $this->search . '%')
                      ->orWhere('asal_instansi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('waktu_kunjungan', 'desc')
            ->paginate($this->paginate);

        $users = User::where('role', 'Anggota')->get();

        return view('livewire.admin.kunjungan.index', [
            'visits' => $visits,
            'users' => $users,
            'title' => $this->title
        ]);
    }

    public function create()
    {
        $this->reset(['nama_pengunjung', 'keperluan', 'asal_instansi', 'user_id']);
        $this->dispatch('showCreateModal');
    }

    public function store()
    {
        $validatedData = $this->validate();
        $validatedData['waktu_kunjungan'] = Carbon::now();
        Visit::create($validatedData);
        $this->dispatch('closeCreateModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Kunjungan Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $visit = Visit::findOrFail($id);
        $this->visit_id = $visit->id;
        $this->nama_pengunjung = $visit->nama_pengunjung;
        $this->keperluan = $visit->keperluan;
        $this->asal_instansi = $visit->asal_instansi;
        $this->user_id = $visit->user_id;
        $this->dispatch('showEditModal');
    }

    public function update()
    {
        $validatedData = $this->validate();
        $visit = Visit::findOrFail($this->visit_id);
        $visit->update($validatedData);
        $this->dispatch('closeEditModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Kunjungan Berhasil Diperbarui']);
    }

    public function confirm($id)
    {
        $this->visit_id = $id;
        $visit = Visit::findOrFail($id);
        $this->nama_pengunjung = $visit->nama_pengunjung;
        $this->keperluan = $visit->keperluan;
        $this->dispatch('showDeleteModal');
    }

    public function destroy()
    {
        Visit::findOrFail($this->visit_id)->delete();
        $this->dispatch('closeDeleteModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Kunjungan Berhasil Dihapus']);
    }

    public function exportExcel()
    {
        return Excel::download(new VisitsExport($this->search), 'data_kunjungan.xlsx');
    }

    public function exportPdf()
    {
        $visits = Visit::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where('nama_pengunjung', 'like', '%' . $this->search . '%')
                    ->orWhere('keperluan', 'like', '%' . $this->search . '%')
                    ->orWhere('asal_instansi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('waktu_kunjungan', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.visits', ['visits' => $visits, 'title' => 'Data Kunjungan']); 
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'data_kunjungan.pdf');
    }
}
