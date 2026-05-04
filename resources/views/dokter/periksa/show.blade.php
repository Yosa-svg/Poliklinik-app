<x-layouts.app :title="'Pemeriksaan - ' . $daftarPoli->pasien->name">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('dokter.periksa.index', auth()->id()) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Form Pemeriksaan Pasien</h1>
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
                        <p class="text-blue-200 text-xs">Keluhan</p>
                        <p class="font-semibold">{{ $daftarPoli->keluhan }}</p>
                    </div>
                    <div>
                        <p class="text-blue-200 text-xs">No. Antrian</p>
                        <span class="inline-block bg-amber-400 text-amber-900 text-xs font-bold px-2.5 py-1 rounded-full">{{ $daftarPoli->no_antrian }}</span>
                    </div>
                    <div>
                        <p class="text-blue-200 text-xs">Jadwal</p>
                        <p class="font-semibold">{{ $daftarPoli->jadwalPeriksa->hari }}</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            @if ($periksa)
                {{-- Edit Mode --}}
                <div class="bg-white rounded-2xl shadow-sm border border-blue-100">
                    <div class="bg-blue-600 px-5 py-3 rounded-t-2xl">
                        <h2 class="font-bold text-white text-sm">Edit Hasil Pemeriksaan</h2>
                    </div>
                    <div class="p-5">
                        <form action="{{ route('dokter.periksa.update', ['dokter' => auth()->id(), 'periksa' => $periksa->id]) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pemeriksaan</label>
                                <input type="date" value="{{ $periksa->tgl_periksa->format('Y-m-d') }}" disabled
                                    class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                            </div>
                            <div>
                                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Pemeriksaan <span class="text-red-500">*</span></label>
                                <textarea name="catatan" id="catatan" rows="6" required
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
            @else
                {{-- Create Mode --}}
                <div class="bg-white rounded-2xl shadow-sm border border-green-100">
                    <div class="bg-green-600 px-5 py-3 rounded-t-2xl">
                        <h2 class="font-bold text-white text-sm">Hasil Pemeriksaan</h2>
                    </div>
                    <div class="p-5">
                        <form action="{{ route('dokter.periksa.store', ['dokter' => auth()->id()]) }}" method="POST" class="space-y-4">
                            @csrf
                            {{-- WAJIB: kirim id_daftar_poli ke controller --}}
                            <input type="hidden" name="id_daftar_poli" value="{{ $daftarPoli->id }}">

                            <div>
                                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Pemeriksaan <span class="text-red-500">*</span></label>
                                <textarea name="catatan" id="catatan" rows="6" required
                                    placeholder="Deskripsikan hasil pemeriksaan, diagnosa, kelainan yang ditemukan, dan penyakit pasien..."
                                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all resize-none @error('catatan') border-red-400 bg-red-50 @enderror">{{ old('catatan') }}</textarea>
                                @error('catatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="biaya_periksa" class="block text-sm font-semibold text-gray-700 mb-1">Biaya Pemeriksaan (Rp)</label>
                                <input type="number" name="biaya_periksa" id="biaya_periksa"
                                    value="{{ old('biaya_periksa', 0) }}" min="0" step="1000"
                                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('biaya_periksa') border-red-400 bg-red-50 @enderror">
                                @error('biaya_periksa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center gap-3 pt-2">
                                <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                                    <i class="fas fa-check"></i> Simpan & Lanjut ke Resep
                                </button>
                                <a href="{{ route('dokter.periksa.index', auth()->id()) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Resep Sidebar --}}
        @if ($periksa)
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-amber-100 sticky top-6">
                    <div class="bg-amber-500 px-5 py-3 rounded-t-2xl">
                        <h2 class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fas fa-prescription-bottle"></i> Resep Obat
                        </h2>
                    </div>
                    <div class="p-4">
                        {{-- Daftar Obat --}}
                        <div class="space-y-2 max-h-80 overflow-y-auto mb-4">
                            @if ($periksa->detailPeriksa->count() > 0)
                                @foreach ($periksa->detailPeriksa as $detail)
                                    <div class="flex items-center justify-between bg-gray-50 rounded-xl px-3 py-2.5">
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $detail->obat->nama_obat }}</p>
                                            <p class="text-xs text-gray-400">{{ $detail->obat->kemasan }}</p>
                                        </div>
                                        <form action="{{ route('dokter.periksa.detail.destroy', ['dokter' => auth()->id(), 'periksa' => $periksa->id, 'detail' => $detail->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="if(confirm('Hapus obat ini?')) this.closest('form').submit()"
                                                class="w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-6 text-gray-400 text-sm">
                                    <i class="fas fa-capsules text-2xl mb-2 opacity-30"></i>
                                    <p>Belum ada obat di resep.</p>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-gray-100 pt-3">
                            <form action="{{ route('dokter.periksa.detail.store', ['dokter' => auth()->id(), 'periksa' => $periksa->id]) }}" method="POST" class="space-y-2">
                                @csrf
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    <i class="fas fa-plus mr-1"></i>Tambah Obat
                                </label>
                                <select name="id_obat" required
                                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-amber-400 transition-all">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach (\App\Models\Obat::all() as $obat)
                                        <option value="{{ $obat->id }}">
                                            {{ $obat->nama_obat }} ({{ $obat->kemasan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition-all">
                                    <i class="fas fa-plus mr-1"></i> Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-layouts.app>
