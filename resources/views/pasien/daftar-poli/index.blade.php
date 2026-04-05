<x-layouts.app title="Pendaftaran Saya">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i> Riwayat Pendaftaran Saya
            </h1>
            <p class="text-gray-500 text-sm mt-1">Status pendaftaran antrian poliklinik Anda.</p>
        </div>
        <a href="{{ route('pasien.poli.index') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all">
            <i class="fas fa-plus"></i> Daftar Poliklinik Baru
        </a>
    </div>

    <x-flash-alert />

    @if ($daftars->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-800 text-left">
                            <th class="px-5 py-3 font-semibold">No. Antrian</th>
                            <th class="px-5 py-3 font-semibold">Poliklinik</th>
                            <th class="px-5 py-3 font-semibold">Dokter</th>
                            <th class="px-5 py-3 font-semibold">Hari</th>
                            <th class="px-5 py-3 font-semibold">Jam</th>
                            <th class="px-5 py-3 font-semibold">Keluhan</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($daftars as $daftar)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="bg-amber-100 text-amber-700 font-bold text-xs px-2.5 py-1 rounded-full">
                                        {{ $daftar->no_antrian }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-800">{{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $daftar->jadwalPeriksa->dokter->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $daftar->jadwalPeriksa->hari }}</td>
                                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                                    {{ $daftar->jadwalPeriksa->jam_mulai }} – {{ $daftar->jadwalPeriksa->jam_selesai }}
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ Str::limit($daftar->keluhan, 40) }}</td>
                                <td class="px-5 py-3">
                                    @if ($daftar->periksa)
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Sudah Diperiksa</span>
                                    @else
                                        <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">Menunggu Periksa</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-xl px-5 py-4 text-sm">
            <i class="fas fa-info-circle mr-2"></i>
            Anda belum membuat pendaftaran.
            <a href="{{ route('pasien.poli.index') }}" class="font-semibold underline hover:text-blue-900">Daftarkan ke Poliklinik</a>
        </div>
    @endif

</x-layouts.app>
