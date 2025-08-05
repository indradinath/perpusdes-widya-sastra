<?php

namespace App\Livewire\Admin\Kategori;

use Livewire\Component;
use App\Models\Kategori;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KategorisExport;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';

    public $nama_kategori, $kategori_id;

    public function render()
    {
        $data = array(
            'title' => 'Data Kategori',
            'kategori' => Kategori::where('nama_kategori','like','%'.$this->search.'%')
                ->orderBy('nama_kategori', 'asc')->paginate($this->paginate),
        );
        return view('livewire.admin.kategori.index', $data);
    }

    public function create(){
       $this->resetValidation();
       $this->reset([
        'nama_kategori',
       ]);
    }

    public function store(){
        $this->validate([
            'nama_kategori'                      => 'required|unique:kategoris,nama_kategori',
        ],

        [
            'nama_kategori.required'                     => 'Nama Kategori Tidak Boleh Kosong',
            'nama_kategori.unique'                       => 'Nama Kategori Sudah Terdaftar',
        ]);

        $kategori               = new kategori;
        $kategori->nama_kategori= $this->nama_kategori;
        $kategori->save();

        $this->dispatch('closeCreateModal');
    }

    public function edit($id){
        $this->resetValidation();

        $kategori = kategori::findOrFail($id);
        $this->nama_kategori            = $kategori->nama_kategori;
        $this->kategori_id              = $kategori->id;
    }

    public function update($id){
        $kategori = kategori::findOrFail($id);

        $this->validate([
            'nama_kategori'                      => 'required|unique:kategoris,nama_kategori,'.$id,
        ],

        [
            'nama_kategori.required'                     => 'Nama Kategori Tidak Boleh Kosong',
            'nama_kategori.unique'                       => 'Nama Kategori Sudah Terdaftar',
        ]);

        $kategori->nama_kategori         = $this->nama_kategori;
        $kategori->save();

        $this->dispatch('closeEditModal');
    }

    public function confirm($id){
        $kategori               = kategori::findOrFail($id);
        $this->nama_kategori    = $kategori->nama_kategori;
        $this->kategori_id      = $kategori->id;
    }

    public function destroy($id){
        $kategori = kategori::findOrFail($id);
        $kategori->delete();

        $this->dispatch('closeDeleteModal');
    }

    public function exportExcel()
    {
        return Excel::download(new KategorisExport($this->search), 'data_kategori.xlsx');
    }

    public function exportPdf()
    {
        $kategoris = Kategori::query()
            ->when($this->search, function ($query) {
                $query->where('nama_kategori', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_kategori', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.kategoris', ['kategoris' => $kategoris, 'title' => 'Data Kategori Buku']); 
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'data_kategori.pdf');
    }
}

