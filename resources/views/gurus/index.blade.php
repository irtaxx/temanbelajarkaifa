<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Guru</h2>
            <button type="button" x-data x-on:click="$dispatch('open-modal', 'tambah-guru')"
                class="inline-flex items-center gap-1.5 shrink-0 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 whitespace-nowrap">
                + Tambah Guru
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
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Nama</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">No. HP</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gurus as $guru)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $guru->nama }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $guru->no_hp ?? '-' }}</td>
                                    <td class="px-4 py-2.5">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-700' => $guru->status === 'aktif',
                                            'bg-gray-100 text-gray-600' => $guru->status !== 'aktif',
                                        ])>{{ ucfirst($guru->status) }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right space-x-2 whitespace-nowrap">
                                        <button type="button" x-data x-on:click="$dispatch('open-modal', 'edit-guru-{{ $guru->id }}')" class="text-indigo-600 hover:underline">Edit</button>
                                        <form action="{{ route('gurus.destroy', $guru) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data guru {{ $guru->nama }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $gurus->links() }}</div>
        </div>
    </div>

    <x-form-modal name="tambah-guru" title="Tambah Guru" :action="route('gurus.store')">
        @include('gurus._form', ['guru' => null, 'modalName' => 'tambah-guru'])
    </x-form-modal>

    @foreach ($gurus as $guru)
        <x-form-modal :name="'edit-guru-'.$guru->id" title="Edit Guru" :action="route('gurus.update', $guru)" method="PUT">
            @include('gurus._form', ['guru' => $guru, 'modalName' => 'edit-guru-'.$guru->id])
        </x-form-modal>
    @endforeach
</x-app-layout>
