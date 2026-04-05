<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poliklinik Sejahtera — Daftar Akun</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/Fa1DqQb7xQ2vcrdIWxfjThSH8CSR7PBEaKcr5lCk++/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-[Inter] bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 flex items-center justify-center p-4">

<div class="w-full max-w-lg">

    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-6 text-center">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg p-1.5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Poliklinik" class="w-full h-full object-contain">
            </div>
            <h1 class="text-white text-xl font-bold">Daftar Akun Pasien</h1>
            <p class="text-blue-300 text-sm mt-1">Poliklinik Sejahtera</p>
        </div>

        {{-- Body --}}
        <div class="px-8 py-8">

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                    <ul class="text-red-600 text-sm space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-user text-sm"></i>
                        </span>
                        <input type="text" name="name" id="name"
                            value="{{ old('name') }}" required autofocus
                            placeholder="Nama Lengkap"
                            class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all @error('name') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" id="email"
                            value="{{ old('email') }}" required
                            placeholder="email@contoh.com"
                            class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all @error('email') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Dua kolom: Alamat & No HP --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </span>
                            <input type="text" name="alamat" id="alamat"
                                value="{{ old('alamat') }}" required
                                placeholder="Jalan, Kota"
                                class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all @error('alamat') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-phone text-sm"></i>
                            </span>
                            <input type="text" name="no_hp" id="no_hp"
                                value="{{ old('no_hp') }}" required
                                placeholder="08xxxxxxxxxx"
                                class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all @error('no_hp') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- No KTP --}}
                <div>
                    <label for="no_ktp" class="block text-sm font-medium text-gray-700 mb-1">No. KTP</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-address-card text-sm"></i>
                        </span>
                        <input type="text" name="no_ktp" id="no_ktp"
                            value="{{ old('no_ktp') }}" required
                            placeholder="16 digit NIK"
                            class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all @error('no_ktp') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('no_ktp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all @error('password') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 bg-blue-700 hover:bg-blue-800 active:scale-95 text-white font-bold rounded-xl tracking-wide uppercase text-sm shadow-lg shadow-blue-700/30 transition-all duration-200 mt-2">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">Login sekarang</a>
            </div>
        </div>
    </div>

    <p class="text-center text-blue-300/60 text-xs mt-4">&copy; {{ date('Y') }} Poliklinik Sejahtera</p>
</div>

</body>
</html>