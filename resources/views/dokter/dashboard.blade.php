<x-layouts.app title="Dashboard Dokter">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Selamat Datang, {{ auth()->user()->nama ?? auth()->user()->name }}!
            </h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan aktivitas pemeriksaan Anda.</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 border-l-4 border-l-green-500">
            <div
                class="w-14 h-14 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Pemeriksaan Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-800">{{ $periksasThisMonth ?? 0 }}</p>
            </div>
        </div>
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 border-l-4 border-l-amber-500">
            <div
                class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-hourglass-end"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Menunggu Periksa</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pendingExaminations ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="w-full">
        {{-- Keluhan Pasien --}}
        @if (isset($commonComplaints) && $commonComplaints->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-bold text-gray-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-chart-bar text-blue-600"></i> Keluhan Pasien Terbanyak Bulan Ini
                </h2>
                <div class="space-y-4">
                    @foreach ($commonComplaints as $keluhan => $count)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $keluhan }}</span>
                                <span
                                    class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $count }}</span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-2 bg-blue-500 rounded-full transition-all"
                                    style="width: {{ ($count / $commonComplaints->sum()) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-400">
                <i class="fas fa-clipboard text-4xl mb-3 opacity-30"></i>
                <p class="text-sm">Belum ada data keluhan pasien bulan ini.</p>
            </div>
        @endif
    </div>

</x-layouts.app>