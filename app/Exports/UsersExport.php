<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil data user yang sama dengan logika pencarian di Livewire Component
        return User::where('nama','like','%'.$this->search.'%')
                    ->orWhere('email','like','%'.$this->search.'%')
                    ->orWhere('role','like','%'.$this->search.'%')
                    ->orWhere('kode_anggota', 'like', '%' . $this->search . '%')
                    ->orWhere('tempat_lahir', 'like', '%' . $this->search . '%')
                    ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
                    ->orderBy('role', 'asc')
                    ->get(); // Gunakan get() karena kita ingin semua data, bukan paginasi
    }

    /**
     * Mengembalikan header untuk kolom Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Kode Anggota',
            'Nama',
            'Email',
            'Role',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Jenis Kelamin',
            'No HP',
            'Alamat',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Memetakan setiap baris data ke format yang diinginkan di Excel.
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->kode_anggota,
            $user->nama,
            $user->email,
            $user->role,
            $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '-', // Format tanggal
            $user->tempat_lahir,
            $user->jenis_kelamin,
            $user->no_hp,
            $user->alamat,
            $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-',
            $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '-',
        ];
    }
}
