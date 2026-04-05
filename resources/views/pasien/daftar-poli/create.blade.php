<x-layouts.app title="Daftar Poliklinik">
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('pasien.daftar-poli.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pendaftaran Poliklinik</h1>
                <p class="text-gray-500 text-sm">Pilih jadwal dokter dan isi keluhan Anda.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('pasien.daftar-poli.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="id_jadwal" class="block text-sm font-semibold text-gray-700 mb-1">
                        Pilih Jadwal Dokter <span class="text-red-500">*</span>
                    </label>
                    <select name="id_jadwal" id="id_jadwal" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('id_jadwal') border-red-400 bg-red-50 @enderror">
                        <option value="">-- Pilih Jadwal --</option>
                        @forelse ($jadwals as $poli_nama => $schedules)
                            <optgroup label="{{ $poli_nama }}">
                                @foreach ($schedules as $jadwal)
                                    <option value="{{ $jadwal->id }}"
                                        {{ request('jadwal') == $jadwal->id ? 'selected' : '' }}>
                                        Dr. {{ $jadwal->dokter->name }} — {{ $jadwal->hari }}
                                        ({{ $jadwal->jam_mulai }} – {{ $jadwal->jam_selesai }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @empty
                            <option disabled>Belum ada jadwal dokter</option>
                        @endforelse
                    </select>
                    @error('id_jadwal') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-1">
                        Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keluhan" id="keluhan" rows="5" required
                        placeholder="Deskripsikan keluhan Anda secara lengkap..."
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all resize-none @error('keluhan') border-red-400 bg-red-50 @enderror">{{ old('keluhan') }}</textarea>
                    @error('keluhan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-400">Jelaskan keluhan Anda secara lengkap</p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Setelah mendaftar, Anda akan mendapatkan nomor antrian dan dapat memantau status pendaftaran di menu "Pendaftaran Saya".
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                        <i class="fas fa-check"></i> Daftar
                    </button>
                    <a href="{{ route('pasien.daftar-poli.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
