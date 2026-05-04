<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — Poliklinik Sejahtera</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/Fa1DqQb7xQ2vcrdIWxfjThSH8CSR7PBEaKcr5lCk++/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-[Inter] h-full">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('containner.partials.sidebar')

        {{-- Konten Utama --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- Header / Topbar --}}
            @include('containner.partials.header')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Flash Messages --}}
                @if(session('message'))
                    <div class="mb-4 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2
                        {{ session('type') === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                        {{ session('type') === 'warning' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                        {{ session('type') === 'error'   ? 'bg-red-50 text-red-700 border border-red-200'     : '' }}
                        {{ !session('type') ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}">
                        <i class="fas
                            {{ session('type') === 'success' ? 'fa-check-circle' : '' }}
                            {{ session('type') === 'warning' ? 'fa-exclamation-triangle' : '' }}
                            {{ session('type') === 'error'   ? 'fa-times-circle'         : '' }}
                            {{ !session('type') ? 'fa-info-circle' : '' }}"></i>
                        {{ session('message') }}
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">
                        <p class="font-semibold flex items-center gap-1 mb-1"><i class="fas fa-times-circle"></i> Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>

            {{-- Footer --}}
            @include('containner.partials.footer')
        </div>

    </div>

    @stack('scripts')
</body>

</html>