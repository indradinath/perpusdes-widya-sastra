<div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-4 text-center">
            <div class="bg-secondary text-white rounded p-4 mb-3">
                <h4 class="text-uppercase mb-2">Anggota Terdaftar</h4>
                <i class="fas fa-users fa-3x mb-3"></i>
                <p class="lead mb-0">{{ $totalAnggotaTerdaftar }} Anggota</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="bg-secondary text-white rounded p-4 mb-3">
                <h4 class="text-uppercase mb-2">Total Koleksi Buku</h4>
                <i class="fas fa-book-open fa-3x mb-3"></i>
                <p class="lead mb-0">{{ $totalKoleksiBuku }} Judul Buku</p>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        {{-- Tautan ke halaman katalog buku lengkap untuk anggota --}}
        @auth
            @if(Auth::user()->role === 'Anggota')
                <a class="btn btn-xl btn-outline-light" href="{{ route('anggota.dashboard.index') }}" wire:navigate>
                    <i class="fas fa-search me-2"></i>
                    Telusuri Katalog Lengkap
                </a>
            @else
                {{-- Jika admin atau role lain, bisa arahkan ke dashboard mereka atau halaman lain --}}
                <a class="btn btn-xl btn-outline-light" href="{{ route('admin.dashboard.index') }}" wire:navigate>
                    <i class="fas fa-arrow-right me-2"></i>
                    Akses Dashboard
                </a>
            @endif
        @else
            {{-- Jika belum login, arahkan ke halaman login --}}
            <a class="btn btn-xl btn-outline-light" href="{{ route('login') }}" wire:navigate>
                <i class="fas fa-sign-in-alt me-2"></i>
                Lihat Selengkapnya
            </a>
        @endauth
    </div>
</div>
