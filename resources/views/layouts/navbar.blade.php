<!-- Navbar -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
            @php
                $userImage = Auth::user()->role === 'Superadmin'
                ? asset('/public/adminlte3/dist/img/user_superadmin.png')
                : (Auth::user()->role === 'Admin'
                ? asset('/public/adminlte3/dist/img/user_admin.png')
                : asset('/public/adminlte3/dist/img/user_anggota.png'));
          @endphp
          <img src="{{ $userImage }}" class="user-image img-circle" alt="User Image">
          <span class="d-none d-md-inline">{{ Str::words(Auth::user()->nama, 2, '') }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header">
            <img src="{{ $userImage }}" class="img-circle" alt="User Image">

            <p>
              {{ Auth::user()->nama }}
              <div>
                @if(Auth::user()->role === 'Superadmin')
                  <span class="badge badge-danger">Superadmin</span>
                @elseif(Auth::user()->role === 'Admin')
                  <span class="badge badge-warning">Admin</span>
                @else
                  <span class="badge badge-primary">Anggota</span>
                @endif
              </div>
            </p>
          </li>

          <!-- Menu Footer-->
          <li class="user-footer">
            @if(Auth::user()->role === 'Anggota')
              <a href="{{ route('anggota.profile.index') }}" class="btn btn-sm btn-default btn-flat">Profile</a>
            @endif

            <a href="#" class="btn btn-sm btn-danger float-right"
               onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
            <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
          </li>
        </ul>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->
