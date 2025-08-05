<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('adminlte3/dist/img/logodesa.png') }}" alt="Logo Desa" style="height: 60px; margin-right: 15px;">
            E-Perpus Widya Sastra
        </a>
        <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#buku">Buku</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#informasi">Informasi</a></li>
                @guest
                    <li class="nav-item mx-0 mx-lg-1">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded btn btn-primary text-white" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item mx-0 mx-lg-1">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded btn btn-outline-light" href="{{ route('register') }}">Daftar</a>
                    </li>
                @else
                    <li class="nav-item dropdown mx-0 mx-lg-1">
                        <a class="nav-link dropdown-toggle py-3 px-0 px-lg-3 rounded" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->nama }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->role === 'Superadmin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.index') }}">Dashboard Superadmin</a></li>
                            @elseif(Auth::user()->role === 'Admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.index') }}">Dashboard Admin</a></li>
                            @elseif(Auth::user()->role === 'Anggota')
                                <li><a class="dropdown-item" href="{{ route('anggota.dashboard.index') }}">Dashboard Anggota</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
