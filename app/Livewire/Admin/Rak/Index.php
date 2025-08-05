<?php

namespace App\Livewire\Admin\Rak;

use App\Models\Rack;
use Livewire\Component;
use App\Exports\RacksExport;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    use WithPagination;

    public $paginate = 10;
    public $search = '';
    public $rack_id;
    public $nama_rak;

    protected $listeners = ['deleteConfirmed' => 'destroy'];

    protected function rules()
    {
        return [
            'nama_rak' => 'required|string|max:255|unique:racks,nama_rak,' . $this->rack_id,
        ];
    }

    protected $messages = [
        'nama_rak.required' => 'Nama rak wajib diisi.',
        'nama_rak.unique' => 'Nama rak sudah ada.',
    ];
    public function render()
    {
        $racks = Rack::query()
            ->when($this->search, function ($query) {
                $query->where('nama_rak', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_rak', 'asc')
            ->paginate($this->paginate);

        return view('livewire.admin.rak.index', [
            'racks' => $racks,
            'title' => 'Data Rak Buku',
        ]);
    }
    public function create()
    {
        $this->reset(['nama_rak', 'rack_id']);
        $this->resetValidation();
        $this->dispatch('showCreateModal');
    }

    public function store()
    {
        $validatedData = $this->validate();

        Rack::create($validatedData);

        $this->dispatch('closeCreateModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Rak Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $rack = Rack::findOrFail($id);
        $this->rack_id = $rack->id;
        $this->nama_rak = $rack->nama_rak;
        $this->dispatch('showEditModal');
    }

    public function update()
    {
        $validatedData = $this->validate();

        $rack = Rack::findOrFail($this->rack_id);
        $rack->update($validatedData);

        $this->dispatch('closeEditModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Rak Berhasil Diperbarui']);
    }

    public function confirm($id)
    {
        $this->rack_id = $id;
        $rack = Rack::findOrFail($id);
        $this->nama_rak = $rack->nama_rak;
        $this->dispatch('showDeleteModal');
    }

    public function destroy()
    {
        Rack::findOrFail($this->rack_id)->delete();
        $this->dispatch('closeDeleteModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Data Rak Berhasil Dihapus']);
    }

    public function exportExcel()
{
    return Excel::download(new RacksExport($this->search), 'data_rak.xlsx');
}

public function exportPdf()
{
    $racks = Rack::query()
        ->when($this->search, function ($query) {
            $query->where('nama_rak', 'like', '%' . $this->search . '%');
        })
        ->orderBy('nama_rak', 'asc')
        ->get();

    $pdf = Pdf::loadView('pdf.racks', ['racks' => $racks, 'title' => 'Data Rak Buku']); 
    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'data_rak.pdf');
}
}
