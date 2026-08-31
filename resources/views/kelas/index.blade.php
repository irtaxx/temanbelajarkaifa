<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Kelas</h2>
            <button type="button" x-data x-on:click="$dispatch('open-modal', 'tambah-kelas')"
                class="inline-flex items-center gap-1.5 shrink-0 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 whitespace-nowrap">
                + Tambah Kelas
            </button>
        </div>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('kelas.index') }}" class="mb-4 flex flex-wrap items-end gap-3 bg-white border border-gray-100 rounded-xl p-4">
                <div class="w-40">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tahun Ajar</label>
                    <select name="tahun_ajar" onchange="this.form.submit()" class="border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach ($daftarTahunAjar as $ta)
                            <option value="{{ $ta }}" @selected($filterTahunAjar === $ta)>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-36">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Semester</label>
                    <select name="semester" onchange="this.form.submit()" class="border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach (['Ganjil', 'Genap'] as $s)
                            <option value="{{ $s }}" @selected($filterSemester === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($filterTahunAjar !== '' || $filterSemester !== '')
                    <a href="{{ route('kelas.index') }}" class="text-sm text-gray-500 hover:text-gray-700 pb-2.5">Reset</a>
                @endif
            </form>

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Nama Kelas</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Jenjang</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Semester</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Tahun Ajar</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Siswa</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas as $k)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $k->nama_kelas }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $k->jenjang }}</td>
                                    <td class="px-4 py-2.5">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-amber-100 text-amber-800' => $k->semester === 'Ganjil',
                                            'bg-sky-100 text-sky-800' => $k->semester === 'Genap',
                                        ])>{{ $k->semester }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-gray-600 tabular-nums">{{ $k->tahun_ajar }}</td>
                                    <td class="px-4 py-2.5 text-right text-gray-600 tabular-nums">{{ $k->jumlah_siswa }}</td>
                                    <td class="px-4 py-2.5 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('kelas.edit', $k) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form action="{{ route('kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $kelas->links() }}</div>
        </div>
    </div>

    <x-form-modal name="tambah-kelas" title="Tambah Kelas" :action="route('kelas.store')">
        @include('kelas._form', ['kelas' => null])
    </x-form-modal>
</x-app-layout>
