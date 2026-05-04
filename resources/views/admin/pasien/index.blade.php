<x-layouts.app title="Data Pasien">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-users text-blue-600"></i> Data Pasien
            </h1>
            <p class="text-gray-500 text-sm mt-1">Daftar seluruh pasien terdaftar.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pasien.export') }}"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <x-flash-alert />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-50 text-blue-800 text-left">
                        <th class="px-5 py-3 font-semibold">No. RM</th>
                        <th class="px-5 py-3 font-semibold">Nama Pasien</th>
                        <th class="px-5 py-3 font-semibold">Email</th>
                        <th class="px-5 py-3 font-semibold">No. KTP</th>
                        <th class="px-5 py-3 font-semibold">No. HP</th>
                        <th class="px-5 py-3 font-semibold">Alamat</th>
                        <th class="px-5 py-3 font-semibold text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pasiens as $pasien)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                    {{ $pasien->no_rm }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-semibold text-gray-800">{{ $pasien->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $pasien->email }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $pasien->no_ktp }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $pasien->no_hp }}</td>
                            <td class="px-5 py-3 text-gray-600 max-w-[150px] truncate">{{ $pasien->alamat }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('pasien.edit', $pasien->id) }}"
                                        class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete(this.form, 'pasien ini')"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-600 hover:bg-red-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-user-slash text-3xl mb-2 opacity-30"></i>
                                <p>Belum ada data pasien terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($pasiens, 'links'))
        <div class="mt-4">{{ $pasiens->links() }}</div>
    @endif

    @push('scripts')
    <script>
        function confirmDelete(form, name) {
            if (confirm('Yakin ingin menghapus ' + name + '?')) form.submit();
        }
    </script>
    @endpush

</x-layouts.app>