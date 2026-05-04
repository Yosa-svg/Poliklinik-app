<x-layouts.app title="Verifikasi Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola konfirmasi pembayaran pasien.</p>
        </div>
        <span class="bg-yellow-100 text-yellow-700 font-bold text-sm px-4 py-2 rounded-xl">
            {{ $menunggu->count() }} Menunggu Verifikasi
        </span>
    </div>

    <x-flash-alert />

    {{-- Menunggu Verifikasi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-yellow-100 bg-yellow-50 flex items-center gap-2">
            <i class="fas fa-hourglass-half text-yellow-600"></i>
            <h2 class="font-bold text-yellow-800">Menunggu Verifikasi</h2>
        </div>

        @if($menunggu->isEmpty())
            <div class="py-10 text-center text-gray-400">
                <i class="fas fa-check-double text-4xl mb-2 block opacity-30"></i>
                <p class="text-sm">Tidak ada pembayaran yang menunggu verifikasi.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3 text-left">Pasien</th>
                            <th class="px-5 py-3 text-left">Dokter / Poli</th>
                            <th class="px-5 py-3 text-left">Tgl Periksa</th>
                            <th class="px-5 py-3 text-right">Total Bayar</th>
                            <th class="px-5 py-3 text-center">Bukti</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($menunggu as $periksa)
                            <tr class="hover:bg-yellow-50 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-gray-800">{{ $periksa->daftarPoli->pasien->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $periksa->daftarPoli->pasien->no_rm ?? '' }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-700">
                                    <p>Dr. {{ $periksa->daftarPoli->jadwalPeriksa->dokter->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-600">
                                    {{ $periksa->tgl_periksa->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-gray-800">
                                    Rp {{ number_format($periksa->total_biaya, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($periksa->bukti_bayar)
                                        <a href="{{ asset('storage/' . $periksa->bukti_bayar) }}" target="_blank"
                                            class="inline-flex items-center gap-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-image"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.pembayaran.konfirmasi', $periksa->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                                onclick="return confirm('Konfirmasi pembayaran ini sebagai LUNAS?')">
                                                <i class="fas fa-check"></i> Konfirmasi
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pembayaran.tolak', $periksa->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                                onclick="return confirm('Tolak bukti pembayaran ini?')">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Sudah Lunas --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-green-100 bg-green-50 flex items-center gap-2">
            <i class="fas fa-check-circle text-green-600"></i>
            <h2 class="font-bold text-green-800">Sudah Lunas</h2>
        </div>

        @if($lunas->isEmpty())
            <div class="py-8 text-center text-gray-400 text-sm">Belum ada pembayaran yang lunas.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3 text-left">Pasien</th>
                            <th class="px-5 py-3 text-left">Dokter / Poli</th>
                            <th class="px-5 py-3 text-left">Tgl Periksa</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($lunas as $periksa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-gray-800">{{ $periksa->daftarPoli->pasien->name ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-600">
                                    Dr. {{ $periksa->daftarPoli->jadwalPeriksa->dokter->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ $periksa->tgl_periksa->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-right font-bold text-green-600">
                                    Rp {{ number_format($periksa->total_biaya, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts.app>