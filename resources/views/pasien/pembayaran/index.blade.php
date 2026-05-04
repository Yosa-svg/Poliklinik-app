<x-layouts.app title="Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            <p class="text-gray-500 text-sm mt-1">Lihat tagihan dan upload bukti pembayaran Anda.</p>
        </div>
    </div>

    <x-flash-alert />

    @if($tagihans->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center text-gray-400">
            <i class="fas fa-file-invoice text-5xl mb-3 block opacity-30"></i>
            <p class="font-semibold">Belum ada tagihan.</p>
            <p class="text-sm mt-1">Tagihan akan muncul setelah pemeriksaan selesai.</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach($tagihans as $periksa)
            @php
                $status      = $periksa->status_bayar;
                $badgeClass  = match($status) {
                    'lunas'                 => 'bg-green-100 text-green-700',
                    'menunggu_verifikasi'   => 'bg-yellow-100 text-yellow-700',
                    default                 => 'bg-red-100 text-red-700',
                };
                $badgeIcon   = match($status) {
                    'lunas'                 => 'fas fa-check-circle',
                    'menunggu_verifikasi'   => 'fas fa-hourglass-half',
                    default                 => 'fas fa-times-circle',
                };
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="font-bold text-gray-800">
                            Dr. {{ $periksa->daftarPoli->jadwalPeriksa->dokter->name ?? '-' }}
                            <span class="text-gray-400 font-normal text-sm">
                                — {{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $periksa->tgl_periksa->format('d F Y') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 {{ $badgeClass }} text-xs font-bold px-3 py-1.5 rounded-full">
                        <i class="{{ $badgeIcon }}"></i>
                        {{ $periksa->status_bayar_label }}
                    </span>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Ringkasan Biaya --}}
                    <div class="space-y-2 text-sm">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">Rincian Biaya</p>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Periksa</span>
                            <span>Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
                        </div>
                        @foreach($periksa->detailPeriksa as $detail)
                        <div class="flex justify-between text-gray-500 text-xs">
                            <span>{{ $detail->obat->nama_obat ?? '-' }}</span>
                            <span>Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="border-t border-gray-100 pt-2 flex justify-between font-bold text-gray-800">
                            <span>Total</span>
                            <span class="text-green-600">Rp {{ number_format($periksa->total_biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Upload / Status Area --}}
                    <div>
                        @if($status === 'lunas')
                            <div class="flex flex-col items-center justify-center h-full gap-2 text-green-600">
                                <i class="fas fa-check-circle text-5xl"></i>
                                <p class="font-bold text-sm">Pembayaran Terverifikasi</p>
                            </div>
                        @elseif($status === 'menunggu_verifikasi')
                            <div class="text-center py-4">
                                <i class="fas fa-hourglass-half text-3xl text-yellow-500 mb-2"></i>
                                <p class="text-sm font-semibold text-yellow-700">Bukti sedang diverifikasi admin</p>
                                @if($periksa->bukti_bayar)
                                    <a href="{{ asset('storage/' . $periksa->bukti_bayar) }}" target="_blank"
                                        class="mt-2 inline-flex items-center gap-1 text-blue-600 hover:underline text-xs">
                                        <i class="fas fa-image"></i> Lihat Bukti Upload
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-3">Upload Bukti Bayar</p>
                            <form action="{{ route('pasien.pembayaran.upload', $periksa->id) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Foto Bukti Transfer / Bayar</label>
                                    <input type="file" name="bukti_bayar" accept="image/*" required
                                        class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl p-1 cursor-pointer">
                                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks: 2MB</p>
                                </div>
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2 rounded-xl transition-colors">
                                    <i class="fas fa-upload mr-1"></i> Upload Bukti
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</x-layouts.app>
