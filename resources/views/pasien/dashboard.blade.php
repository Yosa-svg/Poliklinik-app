<x-layouts.app title="Dashboard Pasien">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan informasi kesehatan Anda.</p>
        </div>
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

    {{-- ═══ BANNER ANTRIAN AKTIF ═════════════════════════════════════════════ --}}
    @if(isset($activeQueue) && $activeQueue)
        <div id="antrian-banner"
            class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-5 mb-6 text-white relative overflow-hidden">
            {{-- Background decoration --}}
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute -right-4 bottom-0 w-20 h-20 bg-white/5 rounded-full"></div>

            <div class="relative">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-400"></span>
                    </span>
                    <p class="font-bold text-sm uppercase tracking-wide text-blue-100">Antrian Aktif</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-blue-200 text-xs mb-1">Poliklinik</p>
                        <p class="font-bold">{{ $activeQueue->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-blue-200 text-xs mb-1">Dokter</p>
                        <p class="font-bold">Dr. {{ $activeQueue->jadwalPeriksa->dokter->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-blue-200 text-xs mb-1">Jadwal</p>
                        <p class="font-bold">
                            {{ $activeQueue->jadwalPeriksa->hari }},
                            {{ $activeQueue->jadwalPeriksa->jam_mulai }}
                        </p>
                    </div>
                    <div>
                        <p class="text-blue-200 text-xs mb-1">No. Antrian Anda</p>
                        <p class="font-bold text-3xl text-yellow-300">#{{ $activeQueue->no_antrian }}</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-white/20 pt-4">
                    <div>
                        <p class="text-blue-200 text-xs mb-1">Sedang Dilayani</p>
                        <p id="no-dilayani" class="font-bold text-2xl" data-jadwal="{{ $activeQueue->id_jadwal }}">
                            {{ $noDilayani > 0 ? '#' . $noDilayani : '-' }}
                        </p>
                    </div>
                    @if(($activeQueue->no_antrian - ($noDilayani ?? 0)) > 0)
                    <div class="bg-white/20 rounded-xl px-4 py-2 text-center">
                        <p class="text-xs text-blue-200">Estimasi Antrian</p>
                        <p class="font-bold text-xl">{{ $activeQueue->no_antrian - ($noDilayani ?? 0) }} orang</p>
                    </div>
                    @else
                    <div class="bg-green-400/30 rounded-xl px-4 py-2 text-center">
                        <p class="text-xs text-green-100">Giliran Anda Segera!</p>
                        <i class="fas fa-bell text-2xl text-yellow-300"></i>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

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
                    @if(isset($lastExamination) && $lastExamination)
                        {{ $lastExamination->tgl_periksa->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- ═══ TABEL JADWAL POLIKLINIK ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-blue-500"></i> Jadwal Poliklinik
            </h2>
            <a href="{{ route('pasien.daftar-poli.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                <i class="fas fa-plus mr-1"></i> Daftar
            </a>
        </div>
        @if(isset($jadwalAll) && $jadwalAll->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">No</th>
                        <th class="px-5 py-3 text-left">Poliklinik</th>
                        <th class="px-5 py-3 text-left">Dokter</th>
                        <th class="px-5 py-3 text-left">Hari</th>
                        <th class="px-5 py-3 text-left">Jam Periksa</th>
                        <th class="px-5 py-3 text-center">No Dilayani</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="jadwal-tbody">
                    @foreach($jadwalAll as $i => $jadwal)
                    <tr class="hover:bg-gray-50 transition-colors" data-jadwal-id="{{ $jadwal->id }}">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">
                            {{ $jadwal->dokter->poli->nama_poli ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-gray-700">Dr. {{ $jadwal->dokter->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $jadwal->hari }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $jadwal->jam_mulai }} – {{ $jadwal->jam_selesai }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="queue-number inline-block bg-blue-100 text-blue-700 font-bold text-xs px-3 py-1 rounded-full"
                                data-id="{{ $jadwal->id }}">
                                {{ $jadwal->no_dilayani > 0 ? '#' . $jadwal->no_dilayani : '-' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-8 text-center text-gray-400 text-sm">
            <i class="fas fa-calendar-times text-3xl mb-2 block opacity-30"></i>
            Belum ada jadwal poli aktif.
        </div>
        @endif
    </div>

    {{-- Riwayat Terbaru --}}
    @if(isset($recentExams) && $recentExams->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-600 text-white px-5 py-3 flex items-center justify-between">
                <h2 class="font-bold flex items-center gap-2">
                    <i class="fas fa-history"></i> Pemeriksaan Terakhir
                </h2>
                <a href="{{ route('pasien.riwayat.index') }}"
                    class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentExams as $exam)
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

    {{-- ═══ REAL-TIME: Laravel Echo ══════════════════════════════════════════ --}}
    @push('scripts')
    <script>
        if (typeof window.Echo !== 'undefined') {
            const rows = document.querySelectorAll('[data-jadwal-id]');
            const activeJadwalId = document.getElementById('no-dilayani')?.dataset?.jadwal;

            rows.forEach(row => {
                const jadwalId = row.dataset.jadwalId;
                window.Echo.channel(`queue.${jadwalId}`)
                    .listen('QueueUpdated', (e) => {
                        const badge = row.querySelector('.queue-number');
                        if (badge) {
                            badge.textContent = e.no_dilayani > 0 ? '#' + e.no_dilayani : '-';
                            badge.classList.add('animate-pulse');
                            setTimeout(() => badge.classList.remove('animate-pulse'), 2000);
                        }

                        if (activeJadwalId && jadwalId == activeJadwalId) {
                            const el = document.getElementById('no-dilayani');
                            if (el) {
                                el.textContent = e.no_dilayani > 0 ? '#' + e.no_dilayani : '-';
                            }
                        }
                    });
            });
        }
    </script>
    @endpush

</x-layouts.app>