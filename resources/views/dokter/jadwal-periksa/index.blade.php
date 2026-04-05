<x-layouts.app title="Jadwal Periksa">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-blue-600"></i> Jadwal Periksa
            </h1>
            <p class="text-gray-500 text-sm mt-1">Kelola jadwal pemeriksaan Anda.</p>
        </div>
        <a href="{{ route('jadwal-periksa.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>

    <x-flash-alert />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-50 text-blue-800 text-left">
                        <th class="px-5 py-3 font-semibold">Hari</th>
                        <th class="px-5 py-3 font-semibold">Jam Mulai</th>
                        <th class="px-5 py-3 font-semibold">Jam Selesai</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($jadwalPeriksa as $jadwal)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-semibold text-gray-800">{{ $jadwal->hari }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $jadwal->jam_mulai }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $jadwal->jam_selesai }}</td>
                            <td class="px-5 py-3">
                                @if ($jadwal->status == 'aktif')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                                @else
                                    <span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">Tidak
                                        Aktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('jadwal-periksa.edit', $jadwal->id) }}"
                                    class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-calendar-times text-3xl mb-2 opacity-30"></i>
                                <p>Belum ada jadwal periksa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>