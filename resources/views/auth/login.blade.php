<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poliklinik Sejahtera — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/Fa1DqQb7xQ2vcrdIWxfjThSH8CSR7PBEaKcr5lCk++/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-[Inter] bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-8 text-center">
            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg p-1.5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Poliklinik" class="w-full h-full object-contain">
            </div>
            <h1 class="text-white text-2xl font-bold">Poliklinik <span class="text-blue-200">Sejahtera</span></h1>
            <p class="text-blue-300 text-sm mt-1"><i class="fas fa-hospital mr-1"></i> Sistem Manajemen Poliklinik</p>
        </div>

        {{-- Card Body --}}
        <div class="px-8 py-8">
            <h2 class="text-blue-700 font-bold text-lg mb-6">Masuk ke Sistem</h2>

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

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" id="email"
                            value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all
                                   @error('email') border-red-400 bg-red-50 @enderror"
                            placeholder="masukkan@email.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" id="password"
                            required
                            class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all
                                   @error('password') border-red-400 bg-red-50 @enderror"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 bg-blue-700 hover:bg-blue-800 active:scale-95 text-white font-bold rounded-xl tracking-wide uppercase text-sm shadow-lg shadow-blue-700/30 hover:shadow-blue-700/50 transition-all duration-200 mt-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>

            {{-- footer link --}}
            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">Daftar di sini</a>
            </div>
        </div>
    </div>

    <p class="text-center text-blue-300/60 text-xs mt-4">&copy; {{ date('Y') }} Poliklinik Sejahtera</p>
</div>

</body>
</html>