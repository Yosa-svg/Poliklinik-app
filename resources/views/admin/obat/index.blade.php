<x-layouts.app title="Data Obat">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-pills text-blue-600"></i> Data Obat
            </h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data obat beserta stok.</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Export Excel --}}
            <a href="{{ route('obat.export') }}"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('obat.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all">
                <i class="fas fa-plus"></i> Tambah Obat
            </a>
        </div>
    </div>

    <x-flash-alert />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-50 text-blue-800 text-left">
                        <th class="px-5 py-3 font-semibold">No</th>
                        <th class="px-5 py-3 font-semibold">Nama Obat</th>
                        <th class="px-5 py-3 font-semibold">Kemasan</th>
                        <th class="px-5 py-3 font-semibold">Harga</th>
                        <th class="px-5 py-3 font-semibold text-center">Stok</th>
                        <th class="px-5 py-3 font-semibold text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($obats as $i => $obat)
                        <tr class="hover:bg-gray-50 transition-colors {{ $obat->isOutOfStock() ? 'bg-red-50' : ($obat->isLowStock() ? 'bg-yellow-50' : '') }}">
                            <td class="px-5 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-800">
                                {{ $obat->nama_obat }}
                                @if($obat->isOutOfStock())
                                    <span class="ml-2 text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">Habis</span>
                                @elseif($obat->isLowStock())
                                    <span class="ml-2 text-xs bg-yellow-100 text-yellow-700 font-bold px-2 py-0.5 rounded-full">Menipis</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $obat->kemasan }}</td>
                            <td class="px-5 py-3 text-gray-800 font-semibold">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($obat->isOutOfStock())
                                    <span class="inline-block bg-red-100 text-red-700 font-bold text-xs px-3 py-1 rounded-full">
                                        0
                                    </span>
                                @elseif($obat->isLowStock())
                                    <span class="inline-block bg-yellow-100 text-yellow-700 font-bold text-xs px-3 py-1 rounded-full">
                                        {{ $obat->stok }}
                                    </span>
                                @else
                                    <span class="inline-block bg-green-100 text-green-700 font-bold text-xs px-3 py-1 rounded-full">
                                        {{ $obat->stok }}
                                    </span>
                                @endif
                            </td>
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
                                            onclick="confirmDelete(this.form, '{{ $obat->nama_obat }}')"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-600 hover:bg-red-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">
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