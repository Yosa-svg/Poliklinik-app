<x-layouts.app title="Daftar Poliklinik & Jadwal">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clinic-medical text-blue-600"></i> Daftar Poliklinik
            </h1>
            <p class="text-gray-500 text-sm mt-1">Lihat jadwal praktek dokter dan pilih poliklinik untuk mendaftar antrian.</p>
        </div>
        <a href="{{ route('pasien.dashboard') }}"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-4 py-2.5 rounded-xl transition-all">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($polis as $poli)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col hover:-translate-y-1 hover:shadow-md transition-all duration-200">

                {{-- Card Header --}}
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ $poli->nama_poli }}</h3>
                    </div>
                    <p class="text-gray-500 text-sm">
                        {{ $poli->keterangan ?? 'Melayani pemeriksaan dan konsultasi kesehatan untuk ' . strtolower($poli->nama_poli) . '.' }}
                    </p>
                </div>

                {{-- Jadwal Dokter --}}
                <div class="bg-gray-50 mx-4 mb-4 rounded-xl p-4 flex-1">
                    <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-3 border-b border-blue-100 pb-2">
                        <i class="fas fa-user-md mr-1"></i> Jadwal Dokter
                    </h4>
                    @if($poli->dokter && $poli->dokter->count() > 0)
                        <div class="space-y-3">
                            @foreach($poli->dokter as $dokter)
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm mb-1">{{ $dokter->name }}</p>
                                    @if($dokter->jadwalPeriksa && $dokter->jadwalPeriksa->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($dokter->jadwalPeriksa as $jadwal)
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <span class="bg-white border border-gray-200 text-gray-700 font-semibold px-2 py-0.5 rounded-md min-w-[68px] text-center">
                                                        {{ $jadwal->hari }}
                                                    </span>
                                                    <i class="far fa-clock text-gray-400"></i>
                                                    {{ substr($jadwal->jam_mulai, 0, 5) }} – {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs text-red-500 italic"><i class="fas fa-exclamation-circle mr-1"></i>Jadwal belum tersedia</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 text-center py-2">Belum ada dokter yang ditugaskan.</p>
                    @endif
                </div>

                {{-- CTA Button --}}
                <div class="px-4 pb-4">
                    <a href="{{ route('pasien.daftar-poli.create', ['id_poli' => $poli->id]) }}"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition-all duration-200">
                        <i class="fas fa-ticket-alt"></i> Daftar Antrian Sekarang
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center text-gray-400">
                <i class="fas fa-hospital-alt text-5xl mb-4 opacity-20"></i>
                <h3 class="text-lg font-semibold mb-1">Belum Ada Data Poliklinik</h3>
                <p class="text-sm">Data poliklinik dan jadwal dokter saat ini belum tersedia di sistem.</p>
            </div>
        @endforelse
    </div>

</x-layouts.app>