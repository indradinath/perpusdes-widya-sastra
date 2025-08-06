<div class="row justify-content-center">
    @forelse($latestBooks as $book)
        <div class="col-md-6 col-lg-4 mb-5">
            <div class="portfolio-item mx-auto" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                    <div class="portfolio-item-caption-content text-center text-white">
                        <i class="fas fa-magnifying-glass fa-3x"></i><br>
                        @auth
                        @if(Auth::user()->role === 'Superadmin' || Auth::user()->role === 'Admin')
                            {{-- Jika Superadmin atau Admin, arahkan ke halaman detail di admin dashboard --}}
                            <a href="{{ route('admin.buku.index', $book->id) }}" class="text-white text-decoration-none" wire:navigate>Lihat Detail</a>
                        @elseif(Auth::user()->role === 'Anggota')
                            {{-- Jika Anggota, arahkan ke halaman detail di anggota dashboard --}}
                            <a href="{{ route('anggota.buku.index', $book->id) }}" class="text-white text-decoration-none" wire:navigate>Lihat Detail</a>
                        @else
                            {{-- Jika role tidak terdefinisi --}}
                            <a href="{{ route('login') }}" class="text-white text-decoration-none" wire:navigate>Login untuk Lihat Detail</a>
                        @endif
                    @else
                        {{-- Jika belum login, arahkan ke halaman login --}}
                        <a href="{{ route('login') }}" class="text-white text-decoration-none" wire:navigate>Login untuk Lihat Detail</a>
                    @endauth
                    </div>
                </div>

                @if($book->gambar_sampul)
                    <img class="img-fluid" src="{{ asset('public/storage/' . $book->gambar_sampul) }}" alt="{{ $book->judul }}" style="height: 250px; object-fit: cover; width: 100%;" />
                @else
                    <img class="img-fluid" src="{{asset('public/template/assets/img/portfolio/default_book.png')}}" alt="Default Book Cover" style="height: 250px; object-fit: cover; width: 100%;" />
                @endif

                <div class="book-info p-3">
                    <h4 class="book-title text-secondary mb-1">{{ Str::limit($book->judul, 30) }}</h4>
                    <p class="book-author text-muted">{{ Str::limit($book->penulis, 25) }}</p>
                    <span class="badge bg-primary">Tersedia: {{ $book->jumlah_tersedia }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <p>Belum ada buku terbaru yang ditambahkan.</p>
        </div>
    @endforelse
</div>
