<x-layouts.app title="Pemeriksaan Pasien">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-stethoscope text-blue-600"></i> Daftar Pasien untuk Pemeriksaan
        </h1>
        <p class="text-gray-500 text-sm mt-1">Kelola status pemeriksaan pasien Anda.</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex border-b border-gray-200 mb-5 gap-1" role="tablist">
        <button id="tab-pending" onclick="switchTab('pending')"
            class="tab-btn px-5 py-2.5 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors">
            <i class="fas fa-hourglass-half mr-1.5"></i> Menunggu Periksa
            <span class="ml-1 bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending->count() }}</span>
        </button>
        <button id="tab-completed" onclick="switchTab('completed')"
            class="tab-btn px-5 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-check-circle mr-1.5"></i> Sudah Diperiksa
            <span class="ml-1 bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $completed->count() }}</span>
        </button>
    </div>

    {{-- Tab: Pending --}}
    <div id="pane-pending">
        @if ($pending->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-blue-50 text-blue-800 text-left">
                                <th class="px-5 py-3 font-semibold">No. Antrian</th>
                                <th class="px-5 py-3 font-semibold">Nama Pasien</th>
                                <th class="px-5 py-3 font-semibold">No. RM</th>
                                <th class="px-5 py-3 font-semibold">Keluhan</th>
                                <th class="px-5 py-3 font-semibold">Jadwal</th>
                                <th class="px-5 py-3 font-semibold text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($pending as $daftar)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3">
                                        <span class="bg-amber-100 text-amber-700 font-bold text-xs px-2.5 py-1 rounded-full">
                                            {{ $daftar->no_antrian }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $daftar->pasien->name }}</td>
                                    <td class="px-5 py-3">
                                        <code class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ $daftar->pasien->no_rm }}</code>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">{{ Str::limit($daftar->keluhan, 40) }}</td>
                                    <td class="px-5 py-3 text-gray-600">
                                        <p class="font-semibold">{{ $daftar->jadwalPeriksa->hari }}</p>
                                        <p class="text-xs text-gray-400">{{ $daftar->jadwalPeriksa->jam_mulai }} – {{ $daftar->jadwalPeriksa->jam_selesai }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <a href="{{ route('dokter.periksa.show', $daftar) }}"
                                            class="inline-flex items-center gap-1 bg-blue-600 text-white hover:bg-blue-700 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-stethoscope"></i> Periksa
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-xl px-5 py-4 text-sm">
                <i class="fas fa-info-circle mr-2"></i> Tidak ada pasien yang menunggu pemeriksaan.
            </div>
        @endif
    </div>

    {{-- Tab: Completed --}}
    <div id="pane-completed" class="hidden">
        @if ($completed->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-blue-50 text-blue-800 text-left">
                                <th class="px-5 py-3 font-semibold">Nama Pasien</th>
                                <th class="px-5 py-3 font-semibold">No. RM</th>
                                <th class="px-5 py-3 font-semibold">Tanggal Periksa</th>
                                <th class="px-5 py-3 font-semibold">Biaya</th>
                                <th class="px-5 py-3 font-semibold text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($completed as $daftar)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $daftar->pasien->name }}</td>
                                    <td class="px-5 py-3">
                                        <code class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ $daftar->pasien->no_rm }}</code>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">
                                        <p>{{ $daftar->periksa->tgl_periksa->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $daftar->periksa->created_at->format('H:i') }}</p>
                                    </td>
                                    <td class="px-5 py-3 font-semibold text-gray-800">
                                        @if ($daftar->periksa->biaya_periksa > 0)
                                            Rp {{ number_format($daftar->periksa->biaya_periksa, 0, ',', '.') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <a href="{{ route('dokter.periksa.edit', $daftar->periksa) }}"
                                            class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-xl px-5 py-4 text-sm">
                <i class="fas fa-info-circle mr-2"></i> Belum ada pasien yang diperiksa.
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function switchTab(tab) {
            const tabs = ['pending', 'completed'];
            tabs.forEach(t => {
                document.getElementById('pane-' + t).classList.toggle('hidden', t !== tab);
                const btn = document.getElementById('tab-' + t);
                if (t === tab) {
                    btn.classList.add('border-blue-600', 'text-blue-600');
                    btn.classList.remove('border-transparent', 'text-gray-500');
                } else {
                    btn.classList.remove('border-blue-600', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                }
            });
        }
    </script>
    @endpush

</x-layouts.app>
