<x-layouts.app title="Data Obat">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-pills text-blue-600"></i> Data Obat
            </h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data obat yang tersedia.</p>
        </div>
        <a href="{{ route('obat.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all">
            <i class="fas fa-plus"></i> Tambah Obat
        </a>
    </div>

    <x-flash-alert />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-50 text-blue-800 text-left">
                        <th class="px-5 py-3 font-semibold">ID</th>
                        <th class="px-5 py-3 font-semibold">Nama Obat</th>
                        <th class="px-5 py-3 font-semibold">Kemasan</th>
                        <th class="px-5 py-3 font-semibold">Harga</th>
                        <th class="px-5 py-3 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($obats as $obat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-400 text-xs font-mono">{{ $obat->id }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-800">{{ $obat->nama_obat }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $obat->kemasan }}</td>
                            <td class="px-5 py-3 text-gray-800 font-semibold">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('obat.edit', $obat->id) }}"
                                        class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete(this.form, 'obat ini')"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-600 hover:bg-red-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 opacity-30"></i>
                                <p>Belum ada data obat</p>
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