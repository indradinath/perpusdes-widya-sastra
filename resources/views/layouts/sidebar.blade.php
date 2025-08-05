<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="nav-link brand-link">
        <img src="{{ asset('adminlte3/dist/img/logodesa.jpg') }}" alt="AdminLTE Logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light">PERPUSDES</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- <li class="nav-item">
                    <a wire:navigate href="{{ route('admin.dashboard.index') }}" class="nav-link @yield('menuAdminDashboard')">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    @if (Auth::user()->role === 'Superadmin' || Auth::user()->role === 'Admin')
                        <a wire:navigate href="{{ route('admin.dashboard.index') }}" class="nav-link {{ Request::routeIs('admin.dashboard.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard Admin</p>
                        </a>
                    @elseif (Auth::user()->role === 'Anggota')
                        <a wire:navigate href="{{ route('anggota.dashboard.index') }}" class="nav-link {{ Request::routeIs('anggota.dashboard.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard Anggota</p>
                        </a>
                    @else
                        {{-- Fallback --}}
                        <a wire:navigate href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    @endif
                </li>

                @if (Auth::user()->role === 'Superadmin' || Auth::user()->role === 'Admin')
                    <li class="nav-header">MENU</li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.user.index')}}" class="nav-link @yield('menuAdminUser')">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.anggota.index')}}" class="nav-link @yield('menuAdminAnggota')">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Anggota
                            </p>
                        </a>
                    </li> --}}

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.kunjungan.index')}}" class="nav-link @yield('menuAdminKunjungan')">
                            <i class="nav-icon fas fa-house-user"></i>
                            <p>
                                Kunjungan
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.banner.index')}}" class="nav-link @yield('menuAdminBanner')">
                            <i class="nav-icon fas fa-images"></i>
                            <p>
                                Hero Banner
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Buku
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('admin.kategori.index')}}" class="nav-link @yield('menuAdminKategori')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Kategori

                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('admin.rak.index')}}" class="nav-link @yield('menuAdminRak')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Rak
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('admin.buku.index')}}" class="nav-link @yield('menuAdminBuku')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Data Buku
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.transaksi.index')}}" class="nav-link @yield('menuAdminTransaksi')">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                Transaksi
                                {{-- <i class="fas fa-angle-left right"></i> --}}
                            </p>
                        </a>
                    </li>
                @endif

                {{-- MENU ANGGOTA --}}
                @if (Auth::user()->role == 'Anggota')
                    <li class="nav-header">MENU ANGGOTA</li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('anggota.buku.index')}}" class="nav-link @yield('menuAnggotaBuku')">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Buku
                                {{-- <i class="fas fa-angle-left right"></i> --}}
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('anggota.transaksi.index')}}" class="nav-link @yield('menuAnggotaTransaksi')">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                Transaksi
                                {{-- <i class="fas fa-angle-left right"></i> --}}
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('anggota.kunjungan.index')}}" class="nav-link @yield('menuAnggotaKunjungan')">
                            <i class="nav-icon fas fa-house-user"></i>
                            <p>
                                Kunjungan
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('anggota.profile.index')}}" class="nav-link @yield('menuAnggotaProfile')">
                            <i class="nav-icon fas fa-house-user"></i>
                            <p>
                                Profil
                            </p>
                        </a>
                    </li>
                @endif

            {{-- HAPUS --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>
