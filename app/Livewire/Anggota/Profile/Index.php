<?php

namespace App\Livewire\Anggota\Profile;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class Index extends Component
{

    public $title = 'Profil Saya';

    public ?string $nama = null;
    public ?string $email = null;
    public ?string $kode_anggota = null;
    public ?string $tanggal_lahir = null;
    public ?string $tempat_lahir = null;
    public ?string $jenis_kelamin = null;
    public ?string $no_hp = null;
    public ?string $alamat = null;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';


    protected $messages = [
        'nama.required' => 'Nama tidak boleh kosong.',
        'email.required' => 'Email tidak boleh kosong.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong.',
        'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
        'tempat_lahir.required' => 'Tempat lahir tidak boleh kosong.',
        'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong.',
        'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
        'no_hp.required' => 'Nomor HP tidak boleh kosong.',
        'alamat.required' => 'Alamat tidak boleh kosong.',
        'current_password.required' => 'Password saat ini tidak boleh kosong.',
        'password.required' => 'Password baru tidak boleh kosong.',
        'password.min' => 'Password baru minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function mount()
    {
        $user = Auth::user();

        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->kode_anggota = $user->kode_anggota;
        $this->tanggal_lahir = $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null;
        $this->tempat_lahir = $user->tempat_lahir;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->no_hp = $user->no_hp;
        $this->alamat = $user->alamat;
    }

    public function render()
    {
        return view('livewire.anggota.profile.index',[
            'title' => $this->title,
        ]);
    }

    public function updateProfileInformation()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'tanggal_lahir' => ['required', 'date'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ];

        $validatedData = $this->validate($rules, $this->messages);

        if ($user->role === 'Anggota') {
            $validatedData['kode_anggota'] = $this->kode_anggota;
            $validatedData['tanggal_lahir'] = $this->tanggal_lahir;
            $validatedData['tempat_lahir'] = $this->tempat_lahir;
            $validatedData['jenis_kelamin'] = $this->jenis_kelamin;
            $validatedData['no_hp'] = $this->no_hp;
            $validatedData['alamat'] = $this->alamat;
        }

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Profil berhasil diperbarui.']);
    }

    public function updatePassword()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rules = [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $this->validate($rules, $this->messages);

        $user->update([
            'password' => bcrypt($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']); // Reset field password
        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Sukses!', 'text' => 'Password berhasil diperbarui.']);
    }
}
