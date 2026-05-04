{{-- Sidebar --}}
<aside id="sidebar" class="w-64 flex-shrink-0 bg-gradient-to-b from-blue-900 to-slate-900 flex flex-col h-full transition-all duration-300">

    {{-- Brand --}}
    <a href="{{ url('/') }}" class="flex items-center gap-3 px-5 py-4 border-b border-white/10 hover:bg-white/5 transition-colors">
        <div class="w-10 h-10 rounded-xl overflow-hidden shadow-md shrink-0 bg-white p-0.5">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Poliklinik" class="w-full h-full object-contain">
        </div>
        <div class="leading-tight">
            <span class="text-white font-bold text-sm tracking-wide">Poliklinik</span><br>
            <span class="text-blue-300 text-xs">Sejahtera</span>
        </div>
    </a>

    {{-- User Panel --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10">
        <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
            <span class="inline-flex items-center gap-1 text-xs text-blue-300">
                <i class="fas fa-circle text-[6px] text-green-400"></i>
                {{ ucfirst(auth()->user()->role ?? 'Guest') }}
            </span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-thin scrollbar-thumb-white/20">
        @auth
            @if(auth()->user()->role === 'admin')
                <p class="px-3 text-xs font-bold text-blue-400 uppercase tracking-widest mb-2 mt-1">Kelola Data</p>

                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="fas fa-tachometer-alt">
                    Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('poliklinik.index') }}" :active="request()->routeIs('poliklinik.*')" icon="fas fa-hospital">
                    Poliklinik
                </x-nav-link>
                <x-nav-link href="{{ route('dokter.index') }}" :active="request()->routeIs('dokter.*')" icon="fas fa-user-md">
                    Dokter
                </x-nav-link>
                <x-nav-link href="{{ route('pasien.index') }}" :active="request()->routeIs('pasien.*')" icon="fas fa-users">
                    Pasien
                </x-nav-link>
                <x-nav-link href="{{ route('obat.index') }}" :active="request()->routeIs('obat.*')" icon="fas fa-pills">
                    Obat
                </x-nav-link>
                <x-nav-link href="{{ route('admin.pembayaran.index') }}" :active="request()->routeIs('admin.pembayaran.*')" icon="fas fa-check-circle">
                    Verifikasi Pembayaran
                </x-nav-link>

            @elseif(auth()->user()->role === 'dokter')
                <p class="px-3 text-xs font-bold text-blue-400 uppercase tracking-widest mb-2 mt-1">Menu Dokter</p>

                <x-nav-link href="{{ route('dokter.dashboard') }}" :active="request()->routeIs('dokter.dashboard')" icon="fas fa-tachometer-alt">
                    Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('jadwal-periksa.index') }}" :active="request()->routeIs('jadwal-periksa.*')" icon="fas fa-calendar-alt">
                    Jadwal Periksa
                </x-nav-link>
                <x-nav-link href="{{ route('dokter.periksa.index', auth()->id()) }}" :active="request()->routeIs('dokter.periksa.*')" icon="fas fa-stethoscope">
                    Pemeriksaan
                </x-nav-link>

            @elseif(auth()->user()->role === 'pasien')
                <p class="px-3 text-xs font-bold text-blue-400 uppercase tracking-widest mb-2 mt-1">Menu Pasien</p>

                <x-nav-link href="{{ route('pasien.dashboard') }}" :active="request()->routeIs('pasien.dashboard')" icon="fas fa-tachometer-alt">
                    Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('pasien.poli.index') }}" :active="request()->routeIs('pasien.poli.*')" icon="fas fa-hospital-user">
                    Poliklinik
                </x-nav-link>
                <x-nav-link href="{{ route('pasien.daftar-poli.index') }}" :active="request()->routeIs('pasien.daftar-poli.*')" icon="fas fa-clipboard-list">
                    Pendaftaran
                </x-nav-link>
                <x-nav-link href="{{ route('pasien.riwayat.index') }}" :active="request()->routeIs('pasien.riwayat.*')" icon="fas fa-history">
                    Riwayat Pendaftaran
                </x-nav-link>
                <x-nav-link href="{{ route('pasien.periksa.index') }}" :active="request()->routeIs('pasien.periksa.*')" icon="fas fa-file-medical-alt">
                    Hasil Pemeriksaan
                </x-nav-link>
                <x-nav-link href="{{ route('pasien.pembayaran.index') }}" :active="request()->routeIs('pasien.pembayaran.*')" icon="fas fa-credit-card">
                    Pembayaran
                </x-nav-link>
            @endif
        @endauth
    </nav>

    {{-- Logout Button --}}
    <div class="p-3 border-t border-white/10">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white/80 border border-white/20 bg-white/5 hover:bg-red-600 hover:border-red-600 hover:text-white transition-all duration-200 group">
                <i class="fas fa-sign-out-alt group-hover:translate-x-1 transition-transform"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>