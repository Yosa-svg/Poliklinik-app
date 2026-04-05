<x-layouts.app :title="'Edit Pemeriksaan - ' . $daftarPoli->pasien->name">
    <div class="max-w-3xl mx-auto space-y-5">

        <div class="flex items-center gap-3">
            <a href="{{ route('dokter.periksa.index', auth()->id()) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Pemeriksaan</h1>
        </div>

        {{-- Info Pasien --}}
        <div class="bg-blue-700 rounded-2xl text-white p-5">
            <h2 class="font-bold text-blue-100 text-xs uppercase tracking-wide mb-3">Informasi Pasien</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-blue-200 text-xs">Nama</p>
                    <p class="font-semibold">{{ $daftarPoli->pasien->name }}</p>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">No. RM</p>
                    <code class="bg-white/20 px-2 py-0.5 rounded text-xs">{{ $daftarPoli->pasien->no_rm }}</code>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">No. HP</p>
                    <p class="font-semibold">{{ $daftarPoli->pasien->no_hp }}</p>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">Keluhan Awal</p>
                    <p class="font-semibold">{{ $daftarPoli->keluhan }}</p>
                </div>
                <div>
                    <p class="text-blue-200 text-xs">Tanggal Periksa</p>
                    <p class="font-semibold">{{ $periksa->tgl_periksa->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Form Edit --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="bg-blue-600 px-5 py-3 rounded-t-2xl">
                <h2 class="font-bold text-white text-sm"><i class="fas fa-edit mr-2"></i>Edit Hasil Pemeriksaan</h2>
            </div>
            <div class="p-5">
                <form action="{{ route('dokter.periksa.update', $periksa) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Pemeriksaan <span class="text-red-500">*</span></label>
                        <textarea name="catatan" id="catatan" rows="8" required
                            placeholder="Deskripsikan hasil pemeriksaan, diagnosa, dan penyakit pasien..."
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all resize-none @error('catatan') border-red-400 bg-red-50 @enderror">{{ old('catatan', $periksa->catatan) }}</textarea>
                        @error('catatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="biaya_periksa" class="block text-sm font-semibold text-gray-700 mb-1">Biaya Pemeriksaan (Rp)</label>
                        <input type="number" name="biaya_periksa" id="biaya_periksa"
                            value="{{ old('biaya_periksa', $periksa->biaya_periksa ?? 0) }}" min="0" step="1000"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('biaya_periksa') border-red-400 bg-red-50 @enderror">
                        @error('biaya_periksa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Resep Obat --}}
                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-prescription-bottle text-amber-500"></i> Resep Obat
                        </h3>
                        @if ($periksa->detailPeriksa->count() > 0)
                            <div class="space-y-2 mb-3">
                                @foreach ($periksa->detailPeriksa as $detail)
                                    <div class="flex items-center justify-between bg-gray-50 rounded-xl px-3 py-2.5">
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $detail->obat->nama_obat }}</p>
                                            <p class="text-xs text-gray-400">{{ $detail->obat->kemasan }} — Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <form action="{{ route('dokter.periksa.detail.destroy', $detail) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="if(confirm('Hapus?')) this.closest('form').submit()"
                                                class="w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-blue-50 text-blue-600 rounded-xl px-4 py-3 text-sm mb-3">
                                <i class="fas fa-info-circle mr-1"></i> Belum ada obat di resep.
                            </div>
                        @endif

                        {{-- Tambah Obat --}}
                        <div class="bg-gray-50 rounded-xl p-3">
                            <form action="{{ route('dokter.periksa.detail.store', $periksa) }}" method="POST" class="flex gap-2">
                                @csrf
                                <select name="id_obat" required
                                    class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-amber-400 transition-all bg-white">
                                    <option value="">-- Tambah Obat --</option>
                                    @foreach (\App\Models\Obat::all() as $obat)
                                        <option value="{{ $obat->id }}">
                                            {{ $obat->nama_obat }} ({{ $obat->kemasan }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition-all">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dokter.periksa.index', auth()->id()) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
