<?php

namespace App\Livewire\Admin\Banner;

use Livewire\Component;
use App\Models\Hero_Banner;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $paginate = 10;
    public $search = '';
    public $banner_id;
    public $judul, $deskripsi, $urutan, $is_active;
    public $gambar;
    public $old_gambar;

    protected $listeners = ['deleteConfirmed' => 'destroy'];

    protected function rules()
    {
        return [
            'judul'   => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan'  => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'gambar'  => 'nullable|image|max:3072',
        ];
    }

    protected $messages = [
        'gambar.image' => 'File harus berupa gambar.',
        'gambar.max' => 'Ukuran gambar maksimal 3MB.',
        'urutan.integer' => 'Urutan harus berupa angka.',
        'urutan.min' => 'Urutan tidak boleh kurang dari 0.',
    ];

    public function render()
    {
        $banners = Hero_Banner::query()
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->paginate);

        return view('livewire.admin.banner.index', [
            'banners' => $banners,
            'title' => 'Data Hero Banner'
        ]);
    }

    public function create()
    {
        $this->reset([
            'judul', 'deskripsi', 'urutan', 'is_active',
            'gambar', 'old_gambar', 'banner_id'
        ]);
        $this->is_active = true;
        $this->urutan = 0;
        $this->resetValidation();
        $this->dispatch('showCreateModal');
    }

    public function store()
    {
        $validatedData = $this->validate();

        if ($this->gambar) {
            $validatedData['gambar'] = $this->gambar->store('hero-banners', 'public');
        } else {
            $validatedData['gambar'] = null;
        }

        Hero_Banner::create($validatedData);

        $this->dispatch('closeCreateModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Hero Banner Berhasil Ditambahkan']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $banner = Hero_Banner::findOrFail($id);
        $this->banner_id = $banner->id;
        $this->judul = $banner->judul;
        $this->deskripsi = $banner->deskripsi;
        $this->urutan = $banner->urutan;
        $this->is_active = $banner->is_active;
        $this->old_gambar = $banner->gambar;
        $this->gambar = null;
        $this->dispatch('showEditModal');
    }

    public function update()
    {
        $validatedData = $this->validate();

        $banner = Hero_Banner::findOrFail($this->banner_id);

        if ($this->gambar) {
            if ($this->old_gambar && Storage::disk('public')->exists($this->old_gambar)) {
                Storage::disk('public')->delete($this->old_gambar);
            }
            $validatedData['gambar'] = $this->gambar->store('hero-banners', 'public');
        } else {
            $validatedData['gambar'] = $this->old_gambar;
        }

        $banner->update($validatedData);

        $this->dispatch('closeEditModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Hero Banner Berhasil Diperbarui']);
    }

    public function confirm($id)
    {
        $this->banner_id = $id;
        $banner = Hero_Banner::findOrFail($id);
        $this->judul = $banner->judul;
        $this->old_gambar = $banner->gambar;
        $this->dispatch('showDeleteModal');
    }

    public function destroy()
    {
        $banner = Hero_Banner::findOrFail($this->banner_id);

        if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        $this->dispatch('closeDeleteModal');
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Hero Banner Berhasil Dihapus']);
    }
}
