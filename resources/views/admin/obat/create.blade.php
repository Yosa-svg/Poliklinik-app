<x-layouts.app title="Tambah Obat">
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('obat.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Obat</h1>
                <p class="text-gray-500 text-sm">Isi data obat baru di bawah ini.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('obat.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_obat" class="block text-sm font-semibold text-gray-700 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_obat" id="nama_obat" value="{{ old('nama_obat') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('nama_obat') border-red-400 bg-red-50 @enderror">
                        @error('nama_obat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="kemasan" class="block text-sm font-semibold text-gray-700 mb-1">Kemasan <span class="text-red-500">*</span></label>
                        <input type="text" name="kemasan" id="kemasan" value="{{ old('kemasan') }}" placeholder="Contoh: Strip, Botol, Tube" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('kemasan') border-red-400 bg-red-50 @enderror">
                        @error('kemasan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0" step="1"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('harga') border-red-400 bg-red-50 @enderror">
                    @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('obat.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>