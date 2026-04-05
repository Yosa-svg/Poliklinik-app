<x-layouts.app title="Dashboard Admin">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-tachometer-alt text-blue-600"></i> Dashboard Admin
            </h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan statistik dan aktivitas terbaru sistem klinik.</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 text-sm text-gray-600 shadow-sm font-medium">
            <i class="far fa-calendar-alt text-blue-500"></i>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Poliklinik --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-200 border-b-4 border-b-cyan-500">
            <div class="w-14 h-14 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-hospital"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Poliklinik</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPoli }}</p>
            </div>
        </div>

        {{-- Dokter --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-200 border-b-4 border-b-green-500">
            <div class="w-14 h-14 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-user-md"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Dokter</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalDokter }}</p>
            </div>
        </div>

        {{-- Pasien --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-200 border-b-4 border-b-blue-500">
            <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Pasien</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPasien }}</p>
            </div>
        </div>

        {{-- Pemeriksaan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-200 border-b-4 border-b-amber-500">
            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Pemeriksaan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPemeriksaan }}</p>
            </div>
        </div>

    </div>

    {{-- Tables Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pendaftaran Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                <h2 class="font-bold text-gray-800">Pendaftaran Terbaru</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentRegistrations as $reg)
                    <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $reg->pasien->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <i class="far fa-clock mr-1"></i>{{ $reg->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shrink-0 ml-2">
                                Antrian #{{ $reg->no_antrian ?? '-' }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                            <strong>Keluhan:</strong> {{ Str::limit($reg->keluhan, 80) }}
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3 opacity-30"></i>
                        <p class="text-sm">Belum Ada Pendaftaran</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pemeriksaan Selesai --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <h2 class="font-bold text-gray-800">Pemeriksaan Selesai</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentExaminations as $exam)
                    <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $exam->daftarPoli->pasien->name ?? 'N/A' }}</p>
                                <p class="text-xs text-green-600 font-semibold mt-0.5">
                                    <i class="fas fa-user-md mr-1"></i>Dr. {{ $exam->daftarPoli->jadwalPeriksa->dokter->name ?? 'N/A' }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg shrink-0 ml-2">
                                {{ $exam->created_at->format('d M Y') }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            <strong>Catatan:</strong> {{ Str::limit($exam->catatan, 80) }}
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-clipboard-check text-4xl mb-3 opacity-30"></i>
                        <p class="text-sm">Belum Ada Pemeriksaan</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</x-layouts.app>