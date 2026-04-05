<x-layouts.app title="Riwayat Hasil Pemeriksaan">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-file-medical-alt text-blue-600"></i> Riwayat Hasil Pemeriksaan
        </h1>
        <p class="text-gray-500 text-sm mt-1">Semua hasil pemeriksaan Anda.</p>
    </div>

    @if ($periksas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach ($periksas as $periksa)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:-translate-y-1 hover:shadow-md transition-all duration-200">

                    {{-- Card Header --}}
                    <div class="bg-blue-700 px-5 py-4 flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-white text-sm">
                                Dr. {{ $periksa->daftarPoli->jadwalPeriksa->dokter->name }}
                            </h3>
                            <p class="text-blue-200 text-xs mt-0.5">
                                {{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}
                            </p>
                        </div>
                        <span class="bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shrink-0">
                            {{ $periksa->tgl_periksa->format('d/m/Y') }}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 space-y-3">
                        {{-- Diagnosa --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Diagnosa & Catatan</p>
                            <p class="text-sm text-gray-700">{{ Str::limit($periksa->catatan, 100) }}</p>
                        </div>

                        {{-- Resep Obat --}}
                        @if ($periksa->detailPeriksa->count() > 0)
                            <div class="border-t border-gray-100 pt-3">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                                    <i class="fas fa-pills mr-1 text-amber-500"></i> Resep Obat
                                </p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($periksa->detailPeriksa as $detail)
                                        <span class="bg-amber-50 border border-amber-200 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-lg">
                                            {{ $detail->obat->nama_obat }}
                                            <span class="text-amber-400">&middot;</span>
                                            {{ $detail->obat->kemasan }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Biaya --}}
                        @if ($periksa->biaya_periksa > 0)
                            <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                                <p class="text-xs text-gray-400">Biaya Pemeriksaan</p>
                                <p class="font-bold text-green-600 text-sm">
                                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer --}}
                    <div class="px-5 pb-4">
                        <a href="{{ route('pasien.periksa.show', $periksa) }}"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-xl transition-all">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-xl px-5 py-6 text-sm text-center">
            <i class="fas fa-info-circle text-2xl mb-2 block opacity-50"></i>
            <p class="mb-3">Anda belum memiliki riwayat pemeriksaan. Daftarkan diri Anda di poliklinik untuk mendapatkan pemeriksaan medis.</p>
            <a href="{{ route('pasien.poli.index') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition-all">
                <i class="fas fa-hospital"></i> Daftar ke Poliklinik
            </a>
        </div>
    @endif

</x-layouts.app>
