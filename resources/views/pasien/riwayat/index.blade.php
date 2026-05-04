<x-layouts.app title="Riwayat Pendaftaran">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pendaftaran Poli</h1>
            <p class="text-gray-500 text-sm mt-1">Semua histori pendaftaran poli Anda.</p>
        </div>
        <a href="{{ route('pasien.daftar-poli.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
            <i class="fas fa-plus"></i> Daftar Baru
        </a>
    </div>

    {{-- Flash Alert --}}
    <x-flash-alert />

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i>
            <h2 class="font-bold text-gray-800">Daftar Riwayat</h2>
            <span class="ml-auto text-xs text-gray-400">Total: {{ $riwayat->count() }} pendaftaran</span>
        </div>

        @if($riwayat->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <i class="fas fa-calendar-times text-5xl mb-3 block opacity-30"></i>
                <p class="font-semibold">Belum ada riwayat pendaftaran.</p>
                <a href="{{ route('pasien.daftar-poli.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
                    Daftar ke poli sekarang →
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3 text-left">No</th>
                            <th class="px-5 py-3 text-left">Poli</th>
                            <th class="px-5 py-3 text-left">Dokter</th>
                            <th class="px-5 py-3 text-left">Hari / Jam</th>
                            <th class="px-5 py-3 text-center">No. Antrian</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($riwayat as $i => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-800">
                                {{ $item->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                Dr. {{ $item->jadwalPeriksa->dokter->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-gray-600">
                                {{ $item->jadwalPeriksa->hari ?? '-' }},
                                {{ $item->jadwalPeriksa->jam_mulai ?? '-' }} –
                                {{ $item->jadwalPeriksa->jam_selesai ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-block bg-blue-100 text-blue-700 font-bold text-xs px-3 py-1 rounded-full">
                                    #{{ $item->no_antrian }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($item->periksa)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        <i class="fas fa-check-circle text-[10px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        <i class="fas fa-clock text-[10px]"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($item->periksa)
                                    <a href="{{ route('pasien.riwayat.show', $item->id) }}"
                                        class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                @else
                                    <span class="text-gray-300 text-xs italic">Belum diperiksa</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts.app>
