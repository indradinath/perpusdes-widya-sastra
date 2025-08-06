<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts1.guest')] class extends Component
{
    public string $nama = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $jenis_kelamin = '';
    public string $alamat = '';
    public string $no_hp = '';
    public string $tempat_lahir = '';
    public string $tanggal_lahir = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'jenis_kelamin'             => ['required', 'in:Laki-laki,Perempuan'],
            'alamat'                    => ['required', 'string'],
            'no_hp'                     => ['required', 'string', 'max:20'],
            'tempat_lahir'              => ['required', 'string', 'max:255'],
            'tanggal_lahir'             => ['required', 'date'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'Anggota';
        if (empty($validated['kode_anggota'])) {
            $lastAnggota = User::where('role', 'Anggota')
                               ->whereNotNull('kode_anggota')
                               ->orderBy('kode_anggota', 'desc')
                               ->first();

            $lastNumber = 0;
            if ($lastAnggota) {
                $lastNumber = (int) substr($lastAnggota->kode_anggota, 3);
            }
            $newNumber = $lastNumber + 1;
            $validated['kode_anggota'] = 'ANG' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }


        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('anggota.dashboard.index', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit.prevent="register">
        <!-- Name -->
        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input wire:model="nama" id="nama" class="block mt-1 w-full" type="text" name="nama" required autofocus autocomplete="nama" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
            <select wire:model="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" name="jenis_kelamin" required>
                <option value="">--Pilih Jenis Kelamin--</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
            <x-text-input wire:model="tanggal_lahir" id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" required />
            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
            <x-text-input wire:model="tempat_lahir" id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" required autocomplete="city" />
            <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="no_hp" :value="__('No HP')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                    +62
                </span>
                <x-text-input wire:model="no_hp" id="no_hp" class="block mt-1 w-full rounded-l-none" type="text" name="no_hp" required autocomplete="tel" />
            </div>
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="alamat" :value="__('Alamat')" />
            <textarea wire:model="alamat" id="alamat" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" name="alamat" rows="3" required autocomplete="street-address"></textarea>
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
