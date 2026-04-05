<x-layouts.app title="Data Poliklinik">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-hospital text-blue-600"></i> Data Poliklinik
            </h1>
            <p class="text-gray-500 text-sm mt-1">Kelola daftar poliklinik yang tersedia.</p>
        </div>
        <a href="{{ route('poliklinik.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200">
            <i class="fas fa-plus"></i> Tambah Poliklinik
        </a>
    </div>

    <x-flash-alert />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-50 text-blue-800 text-left">
                        <th class="px-5 py-3 font-semibold">Nama Poliklinik</th>
                        <th class="px-5 py-3 font-semibold">Deskripsi</th>
                        <th class="px-5 py-3 font-semibold text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($polis as $poli)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-semibold text-gray-800">{{ $poli->nama_poli }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $poli->deskripsi }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('poliklinik.edit', $poli->id) }}"
                                        class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('poliklinik.destroy', $poli->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete(this.form, 'poliklinik ini')"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-600 hover:bg-red-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 opacity-30"></i>
                                <p>Belum ada data poliklinik</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(form, name) {
            if (confirm('Yakin ingin menghapus ' + name + '?')) form.submit();
        }
    </script>
    @endpush

</x-layouts.app>
