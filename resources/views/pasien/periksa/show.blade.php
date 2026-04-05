<x-layouts.app :title="'Hasil Pemeriksaan - Dr. ' . $periksa->daftarPoli->jadwalPeriksa->dokter->name">
    <div class="max-w-3xl mx-auto space-y-5">

        <div class="flex items-center gap-3">
            <a href="{{ route('pasien.periksa.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Hasil Pemeriksaan</h1>
        </div>

        {{-- Dokter & Tanggal --}}
        <div class="bg-blue-700 rounded-2xl text-white p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-blue-200 text-xs mb-1">Dokter</p>
                <h3 class="font-bold text-lg">Dr. {{ $periksa->daftarPoli->jadwalPeriksa->dokter->name }}</h3>
                <p class="text-blue-300 text-sm">{{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli }}</p>
            </div>
            <div>
                <p class="text-blue-200 text-xs mb-1">Tanggal Pemeriksaan</p>
                <h3 class="font-bold text-lg">{{ $periksa->tgl_periksa->format('l, d M Y') }}</h3>
                <p class="text-blue-300 text-sm">Pukul {{ $periksa->created_at->format('H:i') }}</p>
            </div>
        </div>

        {{-- Diagnosa & Catatan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100">
            <div class="bg-blue-600 px-5 py-3 rounded-t-2xl">
                <h2 class="font-bold text-white text-sm">Diagnosa & Catatan Pemeriksaan</h2>
            </div>
            <div class="p-5">
                <p class="text-gray-700 text-sm leading-7 whitespace-pre-wrap">{{ $periksa->catatan }}</p>
            </div>
        </div>

        {{-- Resep Obat --}}
        @if ($details->count() > 0)
            @php $total_obat = 0; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-amber-100">
                <div class="bg-amber-500 px-5 py-3 rounded-t-2xl">
                    <h2 class="font-bold text-white text-sm flex items-center gap-2">
                        <i class="fas fa-prescription-bottle"></i> Resep Obat
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-amber-50 text-amber-800 text-left">
                                <th class="px-5 py-3 font-semibold">Nama Obat</th>
                                <th class="px-5 py-3 font-semibold">Kemasan</th>
                                <th class="px-5 py-3 font-semibold text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($details as $detail)
                                @php $total_obat += $detail->obat->harga; @endphp
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $detail->obat->nama_obat }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $detail->obat->kemasan }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-amber-50 font-bold">
                                <td colspan="2" class="px-5 py-3 text-right text-amber-800">Total Obat:</td>
                                <td class="px-5 py-3 text-right text-amber-800">Rp {{ number_format($total_obat, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Rincian Biaya --}}
        <div class="bg-white rounded-2xl shadow-sm border border-green-100">
            <div class="bg-green-600 px-5 py-3 rounded-t-2xl">
                <h2 class="font-bold text-white text-sm">Rincian Biaya</h2>
            </div>
            <div class="p-5 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Biaya Pemeriksaan</span>
                    <span class="font-semibold">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
                </div>
                @if (isset($total_obat) && $total_obat > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Obat</span>
                        <span class="font-semibold">Rp {{ number_format($total_obat, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="border-t border-gray-100 pt-3 flex justify-between text-base font-bold">
                    <span class="text-gray-800">Total Biaya</span>
                    <span class="text-green-600 text-lg">
                        Rp {{ number_format($periksa->biaya_periksa + ($total_obat ?? 0), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Print Button --}}
        <div class="text-center">
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                <i class="fas fa-print"></i> Cetak Hasil Pemeriksaan
            </button>
        </div>

    </div>
</x-layouts.app>
