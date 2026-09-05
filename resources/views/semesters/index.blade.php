<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Semester</h2>
                <p class="text-sm text-gray-500 mt-0.5">Rentang tanggal tiap semester, dipakai untuk rekap penggajian.</p>
            </div>
            <button type="button" x-data x-on:click="$dispatch('open-modal', 'tambah-semester')"
                class="inline-flex items-center gap-1.5 shrink-0 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 whitespace-nowrap">
                + Tambah Semester
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
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Semester</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Tahun Ajar</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Mulai</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Selesai</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semesters as $s)
                                @php $berjalan = now()->between($s->tanggal_mulai, $s->tanggal_selesai); @endphp
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-amber-100 text-amber-800' => $s->nama === 'Ganjil',
                                            'bg-sky-100 text-sky-800' => $s->nama === 'Genap',
                                        ])>{{ $s->nama }}</span>
                                        @if ($berjalan)
                                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Berjalan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-gray-600 tabular-nums">{{ $s->tahun_ajar }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $s->tanggal_mulai->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $s->tanggal_selesai->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                            <x-action-button x-data x-on:click="$dispatch('open-modal', 'edit-semester-{{ $s->id }}')">Edit</x-action-button>
                                            <form action="{{ route('semesters.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus semester {{ $s->label }}?');">
                                                @csrf @method('DELETE')
                                                <x-action-button type="submit" variant="bahaya">Hapus</x-action-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada semester.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-form-modal name="tambah-semester" title="Tambah Semester" :action="route('semesters.store')">
        @include('semesters._form', ['semester' => null, 'modalName' => 'tambah-semester'])
    </x-form-modal>

    @foreach ($semesters as $s)
        <x-form-modal :name="'edit-semester-'.$s->id" title="Edit Semester" :action="route('semesters.update', $s)" method="PUT">
            @include('semesters._form', ['semester' => $s, 'modalName' => 'edit-semester-'.$s->id])
        </x-form-modal>
    @endforeach
</x-app-layout>
