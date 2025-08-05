<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use App\Exports\UsersExport;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;

class Index extends Component
{
    use withPagination;

    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';

    public $title = 'Data Pengguna';
    public $nama,$email,$role,$password,$password_confirmation,$user_id;
    public $kode_anggota, $tanggal_lahir, $tempat_lahir, $jenis_kelamin, $no_hp, $alamat;
    public $authUserRole;

    public $createModal = false;
    public $editModal = false;
    public $deleteModal = false;

    protected $messages = [
        'nama.required'                     => 'Nama tidak boleh kosong.',
        'email.required'                    => 'Email tidak boleh kosong.',
        'email.email'                       => 'Email tidak valid.',
        'email.unique'                      => 'Email sudah terdaftar.',
        'role.required'                     => 'Role tidak boleh kosong.',
        'password.required'                 => 'Password tidak boleh kosong.',
        'password.min'                      => 'Password minimal 8 karakter.',
        'password.confirmed'                => 'Password konfirmasi tidak sama.',
        'password_confirmation.required'    => 'Password konfirmasi tidak boleh kosong.',
        'kode_anggota.unique'               => 'Kode Anggota sudah terdaftar.',
        'tanggal_lahir.required'            => 'Tanggal Lahir tidak boleh kosong.',
        'tanggal_lahir.date'                => 'Tanggal Lahir tidak valid.',
        'tempat_lahir.required'             => 'Tempat Lahir tidak boleh kosong.',
        'jenis_kelamin.required'            => 'Jenis Kelamin tidak boleh kosong.',
        'jenis_kelamin.in'                  => 'Jenis Kelamin tidak valid.',
        'no_hp.required'                    => 'Nomor HP tidak boleh kosong.',
        'alamat.required'                   => 'Alamat tidak boleh kosong.',
    ];

    public function mount()
    {
        $this->authUserRole = Auth::user()->role;
    }


    public function render()
    {
        $query = User::query();

        if ($this->authUserRole === 'Admin') {
            $query->where('role', 'Anggota');
        }

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_anggota', 'like', '%' . $this->search . '%')
                  ->orWhere('role', 'like', '%' . $this->search . '%');
        }

        $user = $query->orderBy('created_at', 'desc')->paginate($this->paginate);

        return view('livewire.admin.user.index', [
            'user' => $user,
            'title' => $this->title,
        ]);

        // $data = array(
        //     'title' => 'Data Pengguna',
        //     'user' => User::where('nama','like','%'.$this->search.'%')
        //         ->orWhere('email','like','%'.$this->search.'%')
        //         ->orWhere('kode_anggota', 'like', '%' . $this->search . '%')
        //         ->orWhere('role','like','%'.$this->search.'%')
        //         ->orderBy('created_at', 'desc')->paginate($this->paginate),
        // );
        // return view('livewire.admin.user.index', $data);
    }

    public function create(){
       $this->resetValidation();
       $this->reset([
        'kode_anggota',
        'nama',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'email',
        'role',
        'password',
        'password_confirmation',
       ]);
       $this->createModal = true;
    }

    private function generateKodeAnggota()
    {
        // Mendapatkan nomor urut terakhir dari kode_anggota
        $lastAnggota = User::where('role', 'Anggota')
                           ->whereNotNull('kode_anggota')
                           ->orderBy('kode_anggota', 'desc')
                           ->first();

        $lastNumber = 0;
        if ($lastAnggota) {
            $lastNumber = (int) substr($lastAnggota->kode_anggota, 3);
        }

        $newNumber = $lastNumber + 1;
        return 'ANG' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        if ($this->authUserRole === 'Admin' && ($this->role === 'Admin' || $this->role === 'Superadmin')) {
            $this->dispatch('show-alert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki kewenangan untuk menambahkan role ini.'
            ]);
            return;
        }

        $rules= [
            'nama'                      => 'required',
            'email'                     => 'required|email|unique:users,email',
            'role'                      => 'required',
            'password'                  => 'required|min:8|confirmed',
            'password_confirmation'     => 'required',
            'tanggal_lahir'             => 'nullable|date',
            'tempat_lahir'              => 'nullable|string|max:255',
            'jenis_kelamin'             => 'nullable|in:Laki-laki,Perempuan',
            'no_hp'                     => 'nullable|string|max:20',
            'alamat'                    => 'nullable|string',
        ];

        // if ($this->role === 'Anggota') {
        //     $rules['tanggal_lahir'] = 'required|date';
        //     $rules['tempat_lahir'] = 'required|string|max:255';
        //     $rules['jenis_kelamin'] = 'required|in:Laki-laki,Perempuan';
        //     $rules['no_hp'] = 'required|string|max:20';
        //     $rules['alamat'] = 'required|string';
        // }

        // $this->validate($rules, $this->messages);


        if ($this->role == 'Anggota') {
            $rules['kode_anggota'] = 'nullable|string|unique:users,kode_anggota';
            $rules['tanggal_lahir'] = 'required|date';
            $rules['tempat_lahir'] = 'required|string|max:255';
            $rules['jenis_kelamin'] = 'required|in:Laki-laki,Perempuan';
            $rules['no_hp'] = 'required|string|max:20';
            $rules['alamat'] = 'required|string';
        } else {
            $this->kode_anggota = null;
            $this->tanggal_lahir = null;
            $this->tempat_lahir = null;
            $this->jenis_kelamin = null;
            $this->no_hp = null;
            $this->alamat = null;
        }

       $messages = [
            'nama.required'                     => 'Nama Tidak Boleh Kosong',
            'email.required'                    => 'Email Tidak Boleh Kosong',
            'email.email'                       => 'Email Tidak Valid',
            'email.unique'                      => 'Email Sudah Terdaftar',
            'role.required'                     => 'Role Tidak Boleh Kosong',
            'password.required'                 => 'Password Tidak Boleh Kosong',
            'password.min'                      => 'Password Minimal 8 Karakter',
            'password.confirmed'                => 'Password Konfirmasi Tidak Sama',
            'password_confirmation.required'    => 'Password Konfirmasi Tidak Boleh Kosong',
            'kode_anggota.unique'               => 'Kode Anggota Sudah Terdaftar',
            'tanggal_lahir.required'            => 'Tanggal Lahir Tidak Boleh Kosong',
            'tanggal_lahir.date'                => 'Tanggal Lahir Tidak Valid',
            'tempat_lahir.required'             => 'Tempat Lahir Tidak Boleh Kosong',
            'jenis_kelamin.required'            => 'Jenis Kelamin Tidak Boleh Kosong',
            'jenis_kelamin.in'                  => 'Jenis Kelamin Tidak Valid',
            'no_hp.required'                    => 'Nomor HP Tidak Boleh Kosong',
            'alamat.required'                   => 'Alamat Tidak Boleh Kosong',
        ];

        $this->validate($rules, $messages);


        DB::transaction(function () {
            $user               = new User;
            $user->nama         = $this->nama;
            $user->email        = $this->email;
            $user->role         = $this->role;
            $user->password     =   Hash::make($this->password);

            if ($this->role == 'Anggota') {
                $user->kode_anggota = $this->generateKodeAnggota();
                $user->tanggal_lahir = $this->tanggal_lahir;
                $user->tempat_lahir = $this->tempat_lahir;
                $user->jenis_kelamin = $this->jenis_kelamin;
                $user->no_hp = $this->no_hp;
                $user->alamat = $this->alamat;
            } else {

                $user->kode_anggota = null;
                $user->tanggal_lahir = null;
                $user->tempat_lahir = null;
                $user->jenis_kelamin = null;
                $user->no_hp = null;
                $user->alamat = null;
            }

            $user->save();
        });

        $this->dispatch('closeCreateModal');
    }

    public function edit($id){
        $this->resetValidation();

        $user = User::findOrFail($id);

        if ($this->authUserRole === 'Admin' && ($user->role === 'Superadmin' || $user->role === 'Admin')) {
            $this->dispatch('show-alert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki kewenangan untuk mengedit user ini.'
            ]);
            return;
        }

        $user                           = User::findOrFail($id);
        $this->nama                     = $user->nama;
        $this->email                    = $user->email;
        $this->role                     = $user->role;
        $this->password                 = '';
        $this->password_confirmation    = '';
        $this->user_id                  = $user->id;

        $this->kode_anggota             = $user->kode_anggota;
        $this->tanggal_lahir            = $user->tanggal_lahir?->format('Y-m-d');
        $this->tempat_lahir             = $user->tempat_lahir;
        $this->jenis_kelamin            = $user->jenis_kelamin;
        $this->no_hp                    = $user->no_hp;
        $this->alamat                   = $user->alamat;


    }

    public function update($id){
        $user = User::findOrFail($id);

        if ($this->authUserRole === 'Admin' && ($this->role === 'Admin' || $this->role === 'Superadmin') && $user->role !== 'Admin') {
            $this->dispatch('show-alert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki kewenangan untuk mengubah role ke Admin atau Superadmin.'
            ]);
            return;
        }

        $rules = [
            'nama'                      => 'required',
            'email'                     => 'required|email|unique:users,email,'.$id,
            'role'                      => 'required',
            'password'                  => 'nullable|min:8|confirmed',
            'kode_anggota'              => 'nullable|string|unique:users,kode_anggota,' . $id,
            'tanggal_lahir'             => 'nullable|date',
            'tempat_lahir'              => 'nullable|string|max:255',
            'jenis_kelamin'             => 'nullable|in:Laki-laki,Perempuan',
            'no_hp'                     => 'nullable|string|max:20',
            'alamat'                    => 'nullable|string',
        ];


        if ($this->role == 'Anggota') {
            $rules['tanggal_lahir'] = 'required|date';
            $rules['tempat_lahir'] = 'required|string|max:255';
            $rules['jenis_kelamin'] = 'required|in:Laki-laki,Perempuan';
            $rules['no_hp'] = 'required|string|max:20';
            $rules['alamat'] = 'required|string';
        } else {
            $this->kode_anggota = null;
            $this->tanggal_lahir = null;
            $this->tempat_lahir = null;
            $this->jenis_kelamin = null;
            $this->no_hp = null;
            $this->alamat = null;
        }

        $messages = [
            'nama.required'                     => 'Nama Tidak Boleh Kosong',
            'email.required'                    => 'Email Tidak Boleh Kosong',
            'email.email'                       => 'Email Tidak Valid',
            'email.unique'                      => 'Email Sudah Terdaftar',
            'role.required'                     => 'Role Tidak Boleh Kosong',
            'password.min'                      => 'Password Minimal 8 Karakter',
            'password.confirmed'                => 'Password Konfirmasi Tidak Sama',
            'kode_anggota.unique'               => 'Kode Anggota Sudah Terdaftar',
            'tanggal_lahir.required'            => 'Tanggal Lahir Tidak Boleh Kosong',
            'tanggal_lahir.date'                => 'Tanggal Lahir Tidak Valid',
            'tempat_lahir.required'             => 'Tempat Lahir Tidak Boleh Kosong',
            'jenis_kelamin.required'            => 'Jenis Kelamin Tidak Boleh Kosong',
            'jenis_kelamin.in'                  => 'Jenis Kelamin Tidak Valid',
            'no_hp.required'                    => 'Nomor HP Tidak Boleh Kosong',
            'alamat.required'                   => 'Alamat Tidak Boleh Kosong',
        ];

        $this->validate($rules, $messages);

        $user->nama         = $this->nama;
        $user->email        = $this->email;
        $user->role         = $this->role;
        if (filled($this->password)){
            $user->password     =   Hash::make($this->password);
        }

        if ($this->role == 'Anggota') {
            $user->kode_anggota = $this->kode_anggota;
            $user->tanggal_lahir = $this->tanggal_lahir;
            $user->tempat_lahir = $this->tempat_lahir;
            $user->jenis_kelamin = $this->jenis_kelamin;
            $user->no_hp = $this->no_hp;
            $user->alamat = $this->alamat;
        } else {
            $user->kode_anggota = null;
            $user->tanggal_lahir = null;
            $user->tempat_lahir = null;
            $user->jenis_kelamin = null;
            $user->no_hp = null;
            $user->alamat = null;
        }

        $user->save();

        $this->dispatch('closeEditModal');
    }

    public function confirm($id){

        $user = User::findOrFail($id);

        if ($this->authUserRole === 'Admin' && ($user->role === 'Superadmin' || $user->role === 'Admin')) {
            $this->dispatch('show-alert', ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Anda tidak memiliki kewenangan untuk menghapus user ini.']);
            return;
        }

        // $user           = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->nama     = $user->nama;
        $this->email    = $user->email;
        $this->role     = $user->role;
        $this->user_id  = $user->id;
        $this->kode_anggota = $user->kode_anggota;
        $this->tanggal_lahir = $user->tanggal_lahir;
        $this->tempat_lahir = $user->tempat_lahir;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->no_hp = $user->no_hp;
        $this->alamat = $user->alamat;
    }

    public function destroy($id){
        $user = User::findOrFail($id);

        if ($this->authUserRole === 'Admin' && ($user->role === 'Superadmin' || $user->role === 'Admin')) {
            $this->dispatch('show-alert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki kewenangan untuk menghapus user ini.'
            ]);
            return;
        }

        $user->delete();

        $this->dispatch('closeDeleteModal');

    }

    public function exportExcel()
    {
        $search = $this->search;
        $role = $this->authUserRole;

        return Excel::download(new UsersExport($search, $role), 'data_pengguna.xlsx');
    }

    public function exportPdf()
    {
        $query = User::query();

        if (Auth::user()->role === 'Admin') {
            $query->where('role', 'Anggota');
        }

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('role', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_anggota', 'like', '%' . $this->search . '%')
                  ->orWhere('tempat_lahir', 'like', '%' . $this->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                  ->orWhere('alamat', 'like', '%' . $this->search . '%');
        }
        $users = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.users', ['users' => $users, 'title' => $this->title]);
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'data_pengguna.pdf');

        // // Ambil data user yang sama dengan logika pencarian
        // $users = User::where('nama','like','%'.$this->search.'%')
        //                 ->orWhere('email','like','%'.$this->search.'%')
        //                 ->orWhere('role','like','%'.$this->search.'%')
        //                 ->orWhere('kode_anggota', 'like', '%' . $this->search . '%')
        //                 ->orWhere('tempat_lahir', 'like', '%' . $this->search . '%')
        //                 ->orWhere('no_hp', 'like', '%' . $this->search . '%')
        //                 ->orWhere('alamat', 'like', '%' . $this->search . '%')
        //                 ->orderBy('role', 'asc')
        //                 ->get();

        // // Anda perlu membuat view terpisah untuk template PDF
        // $pdf = Pdf::loadView('pdf.users', ['users' => $users, 'title' => $this->title]);

        // // Mengatur ukuran kertas dan orientasi (opsional)
        // $pdf->setPaper('A4', 'landscape');

        // // Memicu download file PDF
        // return response()->streamDownload(function () use ($pdf) {
        //     echo $pdf->stream();
        // }, 'data_pengguna.pdf');
    }
}
