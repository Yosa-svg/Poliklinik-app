<x-layouts.app :title="'Jadwal Dokter - ' . $poli->nama_poli">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('pasien.poli.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $poli->nama_poli }}</h1>
                <p class="text-gray-500 text-sm mt-0.5">{{ $poli->deskripsi }}</p>
            </div>
        </div>

        <h2 class="text-base font-bold text-gray-700 mb-4">Jadwal Dokter Tersedia</h2>

        @if ($jadwals->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-blue-50 text-blue-800 text-left">
                                <th class="px-5 py-3 font-semibold">Dokter</th>
                                <th class="px-5 py-3 font-semibold">Hari</th>
                                <th class="px-5 py-3 font-semibold">Jam Mulai</th>
                                <th class="px-5 py-3 font-semibold">Jam Selesai</th>
                                <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($jadwals as $jadwal)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $jadwal->dokter->name ?? 'N/A' }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $jadwal->hari }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $jadwal->jam_mulai }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $jadwal->jam_selesai }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <a href="{{ route('pasien.daftar-poli.create') }}?jadwal={{ $jadwal->id }}"
                                            class="inline-flex items-center gap-1 bg-green-100 text-green-700 hover:bg-green-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-check"></i> Daftar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-xl px-5 py-4 text-sm">
                <i class="fas fa-info-circle mr-2"></i> Belum ada jadwal dokter untuk poliklinik ini.
            </div>
        @endif

    </div>
</x-layouts.app>
