<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Kelas</h2>
            <a href="{{ route('kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                + Tambah Kelas
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Kelas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Jenjang</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Jumlah Siswa</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kelas as $k)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $k->nama_kelas }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $k->jenjang }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $k->jumlah_siswa }}</td>
                                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('kelas.edit', $k) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form action="{{ route('kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $kelas->links() }}</div>
        </div>
    </div>
</x-app-layout>
