<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="Poliklinik Logo" class="brand-image img-circle elevation-3 opacity-80">
        <span class="brand-text font-weight-light">Poliklinik</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name ?? 'User' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(auth()->check())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dokter')
                        <li class="nav-item">
                            <a href="{{ route('dokter.index') }}" class="nav-link {{ request()->routeIs('dokter.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-md"></i>
                                <p>Manajemen Dokter</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('poli.index') }}" class="nav-link {{ request()->routeIs('poli.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clinic-medical"></i>
                                <p>Manajemen Poli</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('obat.index') }}" class="nav-link {{ request()->routeIs('obat.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pills"></i>
                                <p>Manajemen Obat</p>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role === 'pasien')
                        <li class="nav-item">
                            <a href="{{ route('daftar-poli') }}" class="nav-link {{ request()->routeIs('daftar-poli') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Daftar Poli</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('riwayat-periksa') }}" class="nav-link {{ request()->routeIs('riwayat-periksa') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Periksa</p>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>
    </div>
</aside>
