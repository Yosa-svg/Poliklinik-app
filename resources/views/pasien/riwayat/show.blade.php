<x-layouts.app title="Detail Pemeriksaan">

    {{-- Back --}}
    <div class="mb-5">
        <a href="{{ route('pasien.riwayat.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Detail Kiri --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info Poli & Dokter --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-4">
                    <h1 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-stethoscope"></i> Detail Pemeriksaan
                    </h1>
                    <p class="text-blue-100 text-sm mt-0.5">
                        {{ $riwayat->periksa->tgl_periksa->format('d F Y') }}
                    </p>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Poliklinik</p>
                        <p class="font-semibold text-gray-800">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Dokter</p>
                        <p class="font-semibold text-gray-800">Dr. {{ $riwayat->jadwalPeriksa->dokter->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Hari / Jadwal</p>
                        <p class="font-semibold text-gray-800">
                            {{ $riwayat->jadwalPeriksa->hari }},
                            {{ $riwayat->jadwalPeriksa->jam_mulai }} – {{ $riwayat->jadwalPeriksa->jam_selesai }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">No. Antrian</p>
                        <span class="inline-block bg-blue-100 text-blue-700 font-bold text-xs px-3 py-1 rounded-full">
                            #{{ $riwayat->no_antrian }}
                        </span>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-gray-400 text-xs mb-1">Keluhan</p>
                        <p class="font-semibold text-gray-800">{{ $riwayat->keluhan }}</p>
                    </div>
                </div>
            </div>

            {{-- Catatan Dokter --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-notes-medical text-blue-500"></i> Catatan Dokter
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">{{ $riwayat->periksa->catatan ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>

            {{-- Resep Obat --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-pills text-purple-500"></i> Resep Obat
                    </h2>
                </div>
                @if($riwayat->periksa->detailPeriksa->isEmpty())
                    <div class="p-6 text-center text-gray-400">
                        <i class="fas fa-prescription-bottle text-3xl mb-2 block opacity-30"></i>
                        <p class="text-sm">Tidak ada resep obat.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3 text-left">Nama Obat</th>
                                    <th class="px-6 py-3 text-left">Kemasan</th>
                                    <th class="px-6 py-3 text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($riwayat->periksa->detailPeriksa as $detail)
                                <tr>
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $detail->obat->nama_obat ?? '-' }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $detail->obat->kemasan ?? '-' }}</td>
                                    <td class="px-6 py-3 text-right text-gray-800">
                                        Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Kanan: Ringkasan Biaya --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-receipt text-green-500"></i> Ringkasan Biaya
                    </h2>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Biaya Periksa</span>
                        <span class="font-semibold">
                            Rp {{ number_format($riwayat->periksa->biaya_periksa ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    @foreach($riwayat->periksa->detailPeriksa as $detail)
                    <div class="flex justify-between text-gray-600">
                        <span>{{ $detail->obat->nama_obat ?? '-' }}</span>
                        <span>Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-gray-800">
                        <span>Total</span>
                        <span class="text-green-600 text-base">
                            Rp {{ number_format($riwayat->periksa->total_biaya, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Status Bayar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-wallet text-blue-500"></i> Status Pembayaran
                    </h2>
                </div>
                <div class="p-5">
                    @php $status = $riwayat->periksa->status_bayar; @endphp
                    @if($status === 'lunas')
                        <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 font-bold px-4 py-2 rounded-xl text-sm">
                            <i class="fas fa-check-circle"></i> LUNAS
                        </span>
                    @elseif($status === 'menunggu_verifikasi')
                        <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 font-bold px-4 py-2 rounded-xl text-sm">
                            <i class="fas fa-hourglass-half"></i> Menunggu Verifikasi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 bg-red-100 text-red-700 font-bold px-4 py-2 rounded-xl text-sm">
                            <i class="fas fa-times-circle"></i> Belum Bayar
                        </span>
                        <div class="mt-3">
                            <a href="{{ route('pasien.pembayaran.index') }}"
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                                <i class="fas fa-upload"></i> Upload Bukti Bayar
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
