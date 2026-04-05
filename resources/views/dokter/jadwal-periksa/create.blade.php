<x-layouts.app title="Tambah Jadwal Periksa">
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('jadwal-periksa.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Jadwal Periksa</h1>
                <p class="text-gray-500 text-sm">Isi jadwal praktek Anda di bawah ini.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('jadwal-periksa.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="hari" class="block text-sm font-semibold text-gray-700 mb-1">Hari <span class="text-red-500">*</span></label>
                    <select name="hari" id="hari" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('hari') border-red-400 bg-red-50 @enderror">
                        <option value="">-- Pilih Hari --</option>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                    @error('hari') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('jam_mulai') border-red-400 bg-red-50 @enderror">
                        @error('jam_mulai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('jam_selesai') border-red-400 bg-red-50 @enderror">
                        @error('jam_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('jadwal-periksa.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
