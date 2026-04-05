{{-- Header / Topbar --}}
<header class="bg-blue-800 shadow-md px-6 py-3 flex items-center justify-between shrink-0">

    {{-- Left: Menu Toggle + Brand Mini --}}
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" type="button"
            class="text-white/80 hover:text-white transition-colors focus:outline-none">
            <i class="fas fa-bars text-lg"></i>
        </button>
        <a href="{{ url('/') }}" class="flex items-center gap-2 md:hidden">
            <div class="w-8 h-8 rounded-lg overflow-hidden bg-white p-0.5 shadow-sm">
                <img src="{{ asset('images/logo.png') }}" alt="Poliklinik" class="w-full h-full object-contain">
            </div>
            <span class="text-white font-bold text-sm tracking-wide">Poliklinik</span>
        </a>
    </div>

    {{-- Right: User Dropdown --}}
    <div class="relative" id="user-menu-wrapper">
        <button id="user-menu-btn" type="button"
            class="flex items-center gap-2 text-white hover:text-white/80 transition-colors focus:outline-none">
            <div class="w-8 h-8 rounded-full bg-blue-600 border-2 border-white/30 flex items-center justify-center text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <span class="hidden md:inline text-sm font-semibold">{{ auth()->user()->name ?? 'Admin' }}</span>
            <i class="fas fa-chevron-down text-xs opacity-70"></i>
        </button>

        {{-- Dropdown --}}
        <div id="user-dropdown"
            class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden z-50">

            {{-- User Info Header --}}
            <div class="bg-blue-800 px-4 py-3 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg mx-auto mb-1">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <p class="text-white font-semibold text-sm">{{ auth()->user()->name ?? 'Admin' }}</p>
                <span class="inline-block mt-1 bg-white text-blue-800 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                </span>
            </div>

            {{-- Menu Items --}}
            <div class="py-1 px-2">
                <a href="#"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-user-cog text-gray-400 w-4"></i> Profil Saya
                </a>

                <div class="my-1 border-t border-gray-100"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors font-semibold">
                        <i class="fas fa-sign-out-alt w-4"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // Toggle Dropdown
    document.getElementById('user-menu-btn').addEventListener('click', function() {
        document.getElementById('user-dropdown').classList.toggle('hidden');
    });
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('user-menu-wrapper');
        if (!wrapper.contains(e.target)) {
            document.getElementById('user-dropdown').classList.add('hidden');
        }
    });

    // Toggle Sidebar
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    });
</script>