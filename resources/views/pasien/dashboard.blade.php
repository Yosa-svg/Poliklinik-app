<x-layouts.app title="Dashboard Pasien">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan informasi kesehatan Anda.</p>
        </div>

        {{-- Card Mini No. RM --}}
        <div class="bg-blue-50 border border-blue-100 px-4 py-2 rounded-xl flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-id-card"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold tracking-wide uppercase">No. Rekam Medis</p>
                <p class="font-bold text-blue-700 text-lg leading-tight">{{ auth()->user()->no_rm ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

        <div class="bg-green-600 text-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-green-100 uppercase tracking-wide">Total Pemeriksaan</p>
                <p class="text-3xl font-bold">{{ $totalExaminations ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-cyan-600 text-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-hospital"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-cyan-100 uppercase tracking-wide">Spesialis Dikunjungi</p>
                <p class="text-3xl font-bold">{{ isset($doctorSpecialists) ? $doctorSpecialists->count() : 0 }}</p>
            </div>
        </div>

        <div class="bg-blue-600 text-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-calendar"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-wide">Pemeriksaan Terakhir</p>
                <p class="text-lg font-bold">
                    @if (isset($lastExamination) && $lastExamination)
                        {{ $lastExamination->tgl_periksa->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Jadwal & Riwayat --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Jadwal Berikutnya --}}
            @if (isset($nextAppointment) && $nextAppointment)
                <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
                    <div class="bg-green-600 text-white px-5 py-3">
                        <h2 class="font-bold flex items-center gap-2">
                            <i class="fas fa-calendar-check"></i> Jadwal Berikutnya
                        </h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Dokter</p>
                            <p class="font-semibold">Dr. {{ $nextAppointment->jadwalPeriksa->dokter->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Poliklinik</p>
                            <p class="font-semibold">{{ $nextAppointment->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">No. Antrian</p>
                            <span class="inline-block bg-green-100 text-green-700 font-bold text-xs px-3 py-1 rounded-full">
                                {{ $nextAppointment->no_antrian ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Keluhan</p>
                            <p class="font-semibold">{{ $nextAppointment->keluhan }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Riwayat Pemeriksaan --}}
            @if (isset($recentExams) && $recentExams->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-600 text-white px-5 py-3 flex items-center justify-between">
                        <h2 class="font-bold flex items-center gap-2">
                            <i class="fas fa-history"></i> Pemeriksaan Terakhir
                        </h2>
                        <a href="{{ route('pasien.periksa.index') }}"
                            class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($recentExams as $exam)
                            <div class="p-4 border-l-4 border-l-cyan-500">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 text-sm">
                                            Dr. {{ $exam->daftarPoli->jadwalPeriksa->dokter->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <i class="fas fa-clinic-medical mr-1"></i>
                                            {{ $exam->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}
                                            &middot;
                                            <i class="far fa-calendar-alt mr-1 ml-1"></i>
                                            {{ $exam->tgl_periksa->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1.5">
                                            <strong>Diagnosa:</strong> {{ Str::limit($exam->catatan, 80) }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            <strong>Obat:</strong>
                                            @if ($exam->detailPeriksa->count() > 0)
                                                {{ Str::limit($exam->detailPeriksa->pluck('obat.nama_obat')->implode(', '), 60) }}
                                            @else
                                                <em class="text-gray-400">Tidak ada resep</em>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-green-600 font-bold text-sm">
                                            Rp {{ number_format($exam->biaya_periksa, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Spesialis Langganan --}}
        <div>
            @if (isset($doctorSpecialists) && $doctorSpecialists->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                            <i class="fas fa-user-md text-cyan-600"></i> Spesialis Dikunjungi
                        </h2>
                    </div>
                    <ul class="divide-y divide-gray-100 text-sm">
                        @foreach ($doctorSpecialists as $specialist)
                            <li class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors">
                                <span class="text-gray-700">{{ $specialist }}</span>
                                <i class="fas fa-check-circle text-green-500 text-sm"></i>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

    </div>

</x-layouts.app>