{{--
    ============================================================
    resources/views/admin/obat/create.blade.php
    ============================================================
    PERAN (MVC)  : View — halaman CREATE (form tambah obat baru)
    CONTROLLER   : ObatController@create  (tampilkan form)
                   ObatController@store   (proses simpan)
    ROUTE TAMPIL : GET  /obat/create  (obat.create)
    ROUTE SUBMIT : POST /obat         (obat.store)

    ALUR:
    1. Admin klik "Tambah Obat" di halaman index
    2. Browser membuka halaman ini (GET)
    3. Admin mengisi form dan klik "Simpan"
    4. Form di-submit ke POST /obat → store() memvalidasi & menyimpan
    5. Jika berhasil → redirect ke index dengan notifikasi sukses
    6. Jika gagal validasi → kembali ke form ini dengan pesan error
       dan data lama tetap terisi (via helper old())
    ============================================================
--}}
<x-layouts.app title="Tambah Obat">
    <div class="max-w-2xl mx-auto">

        {{-- ── HEADER HALAMAN ─────────────────────────────────────── --}}
        <div class="flex items-center gap-3 mb-6">
            {{-- Tombol kembali ke halaman index --}}
            <a href="{{ route('obat.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Obat</h1>
                <p class="text-gray-500 text-sm">Isi data obat baru di bawah ini.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            {{--
                FORM TAMBAH OBAT
                - action: tujuan submit → route 'obat.store' = POST /obat
                - method="POST": satu-satunya method yang didukung HTML form
                - @csrf: wajib ada! Laravel menolak POST tanpa token CSRF
                  (Cross-Site Request Forgery protection)
            --}}
            <form action="{{ route('obat.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- ── BARIS 1: Nama Obat & Kemasan ──────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Field: Nama Obat --}}
                    <div>
                        <label for="nama_obat" class="block text-sm font-semibold text-gray-700 mb-1">
                            Nama Obat <span class="text-red-500">*</span>
                        </label>
                        {{--
                            value="{{ old('nama_obat') }}":
                            Jika validasi gagal dan pengguna dikembalikan ke form,
                            nilai yang sudah diketik sebelumnya tetap muncul (tidak hilang).
                            old() membaca data dari sesi flash Laravel.

                            @error('nama_obat') ... @enderror:
                            Tambahkan class merah pada input jika field ini gagal validasi.
                        --}}
                        <input type="text" name="nama_obat" id="nama_obat" value="{{ old('nama_obat') }}" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('nama_obat') border-red-400 bg-red-50 @enderror">
                        {{-- Tampilkan pesan error validasi untuk field nama_obat --}}
                        @error('nama_obat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Field: Kemasan --}}
                    <div>
                        <label for="kemasan" class="block text-sm font-semibold text-gray-700 mb-1">
                            Kemasan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kemasan" id="kemasan" value="{{ old('kemasan') }}" placeholder="Contoh: Strip, Botol, Tube" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('kemasan') border-red-400 bg-red-50 @enderror">
                        @error('kemasan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ── BARIS 2: Harga & Stok Awal ─────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Field: Harga --}}
                    <div>
                        <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        {{--
                            type="number": browser menampilkan input angka
                            min="0": validasi sisi klien (browser), tidak boleh negatif
                            step="1": hanya boleh bilangan bulat
                            Validasi sisi server juga ada di ObatController@store
                        --}}
                        <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0" step="1"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('harga') border-red-400 bg-red-50 @enderror">
                        @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Field: Stok Awal --}}
                    <div>
                        <label for="stok" class="block text-sm font-semibold text-gray-700 mb-1">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        {{--
                            value="{{ old('stok', 0) }}":
                            Nilai default awal adalah 0 (parameter kedua old()).
                            Jika form pernah disubmit tapi gagal, tampilkan nilai lama.
                        --}}
                        <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" required min="0"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all @error('stok') border-red-400 bg-red-50 @enderror">
                        @error('stok') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ── TOMBOL AKSI ──────────────────────────────────────── --}}
                <div class="flex items-center gap-3 pt-2">
                    {{-- Tombol Simpan → submit form ke POST /obat --}}
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    {{-- Tombol Kembali → batal, kembali ke halaman index --}}
                    <a href="{{ route('obat.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>