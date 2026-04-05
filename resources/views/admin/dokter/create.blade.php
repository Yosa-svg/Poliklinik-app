<x-layouts.app title="Tambah Dokter">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('dokter.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Dokter</h1>
                <p class="text-gray-500 text-sm">Isi data dokter baru di bawah ini.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('dokter.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Dokter <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('name') border-red-400 bg-red-50 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('email') border-red-400 bg-red-50 @enderror">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- No KTP --}}
                    <div>
                        <label for="no_ktp" class="block text-sm font-semibold text-gray-700 mb-1">No. KTP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_ktp" id="no_ktp" value="{{ old('no_ktp') }}" maxlength="16" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('no_ktp') border-red-400 bg-red-50 @enderror">
                        @error('no_ktp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1">No. HP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('no_hp') border-red-400 bg-red-50 @enderror">
                        @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="alamat" rows="3" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all resize-none @error('alamat') border-red-400 bg-red-50 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Poli --}}
                <div>
                    <label for="id_poli" class="block text-sm font-semibold text-gray-700 mb-1">Poli <span class="text-red-500">*</span></label>
                    <select name="id_poli" id="id_poli" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('id_poli') border-red-400 bg-red-50 @enderror">
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_poli') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required minlength="6"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('password') border-red-400 bg-red-50 @enderror">
                    <p class="mt-1 text-xs text-gray-400">Minimal 6 karakter</p>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('dokter.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>