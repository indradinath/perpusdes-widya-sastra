<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component
{
    public string $nama = '';
    public string $email = '';
    public ?string $kode_anggota = null;
    public ?string $tanggal_lahir = null;
    public ?string $tempat_lahir = null;
    public ?string $jenis_kelamin = null;
    public ?string $no_hp = null;
    public ?string $alamat = null;


    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->nama = Auth::user()->nama;
        $this->email = Auth::user()->email;
        $this->kode_anggota = Auth::user()->kode_anggota;
        $this->tanggal_lahir = Auth::user()->tanggal_lahir ? Auth::user()->tanggal_lahir->format('Y-m-d') : null;
        $this->tempat_lahir = Auth::user()->tempat_lahir;
        $this->jenis_kelamin = Auth::user()->jenis_kelamin;
        $this->no_hp = Auth::user()->no_hp;
        $this->alamat = Auth::user()->alamat;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'tanggal_lahir' => ['nullable', 'date'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->nama);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input wire:model="nama" id="nama" name="nama" type="text" class="mt-1 block w-full" required autofocus autocomplete="nama" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mt-4">
            <x-input-label for="kode_anggota" :value="__('Kode Anggota')" />
            <x-text-input wire:model="kode_anggota" id="kode_anggota" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" readonly disabled />
            {{-- Tidak perlu input-error karena ini readonly dan digenerate sistem --}}
        </div>

        <div class="mt-4">
            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
            <select wire:model="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" name="jenis_kelamin">
                <option value="">--Pilih Jenis Kelamin--</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
            <x-text-input wire:model="tanggal_lahir" id="tanggal_lahir" type="date" class="mt-1 block w-full" autocomplete="bday" />
            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
            <x-text-input wire:model="tempat_lahir" id="tempat_lahir" type="text" class="mt-1 block w-full" autocomplete="address-level2" />
            <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="no_hp" :value="__('No HP')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                    +62
                </span>
                <x-text-input wire:model="no_hp" id="no_hp" class="mt-1 block w-full rounded-l-none" type="text" autocomplete="tel-national" />
            </div>
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="alamat" :value="__('Alamat')" />
            <textarea wire:model="alamat" id="alamat" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="3" autocomplete="street-address"></textarea>
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
