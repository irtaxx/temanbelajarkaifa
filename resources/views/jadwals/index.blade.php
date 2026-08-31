<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Jadwal Mengajar</h2>
            <button type="button" x-data x-on:click="$dispatch('open-modal', 'tambah-jadwal')"
                class="inline-flex items-center gap-1.5 shrink-0 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 whitespace-nowrap">
                + Tambah Jadwal
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

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Hari</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Jam</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Guru</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Kelas</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Mapel</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwals as $j)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5 text-gray-900">{{ $j->hari === 'Jumat' ? "Jum'at" : $j->hari }}</td>
                                    <td class="px-4 py-2.5 text-gray-600 tabular-nums">{{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $j->guru->nama }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">
                                        {{ $j->kelas->nama_kelas }}
                                        <span class="text-gray-400">· {{ $j->kelas->semester }} {{ $j->kelas->tahun_ajar }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $j->mapel ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('jadwals.edit', $j) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form action="{{ route('jadwals.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada jadwal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $jadwals->links() }}</div>
        </div>
    </div>

    <x-form-modal name="tambah-jadwal" title="Tambah Jadwal" :action="route('jadwals.store')" max-width="xl">
        @include('jadwals._form', ['jadwal' => null])
    </x-form-modal>
</x-app-layout>
