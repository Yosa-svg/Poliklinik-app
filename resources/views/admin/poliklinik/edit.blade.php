<x-layouts.app title="Edit Poliklinik">
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('poliklinik.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Poliklinik</h1>
                <p class="text-gray-500 text-sm">Perbarui data poliklinik di bawah ini.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('poliklinik.update', $poli) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="nama_poli" class="block text-sm font-semibold text-gray-700 mb-1">Nama Poliklinik <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_poli" id="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}"
                        required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('nama_poli') border-red-400 bg-red-50 @enderror">
                    @error('nama_poli') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan <span
                            class="text-red-500">*</span></label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all resize-none @error('keterangan') border-red-400 bg-red-50 @enderror">{{ old('keterangan', $poli->keterangan) }}</textarea>
                    @error('keterangan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('poliklinik.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>