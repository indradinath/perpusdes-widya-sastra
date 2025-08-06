{{-- resources/views/livewire/frontend/hero-carousel.blade.php --}}
<div>
    @if($banners->count() > 0)
        <div id="heroCarousel" class="carousel slide w-100" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($banners as $key => $banner)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($banners as $banner)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="carousel-img-ratio">
                            <img src="{{ asset('public/storage/' . $banner->gambar) }}" class="d-block w-100 h-100" alt="{{ $banner->judul }}" style="object-fit: cover;">
                        </div>

                        <div class="carousel-caption d-none d-md-block text-center bg-dark py-2 rounded" style="--bs-bg-opacity: .7;">
                            <h5 class="fw-bolder">{{ $banner->judul }}</h5>
                            <p>{{ Str::limit($banner->deskripsi, 50) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @else
        {{-- <img class="masthead-avatar mb-5" src="{{asset('template/assets/img/avataaars.svg')}}" alt="..." />
        <h1 class="masthead-heading text-uppercase mb-0">PERPUSDES WIDYA SASTRA</h1>
        <p class="masthead-subheading font-weight-light mb-0">Meningkatkan Literasi, Membangun Masyarakat Cerdas</p> --}}
    @endif
</div>
