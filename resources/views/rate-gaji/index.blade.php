<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rate Gaji per Sesi</h2>
            <a href="{{ route('rate-gaji.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                + Tambah Rate
            </a>
        </div>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <p class="text-sm text-gray-500 mb-4 px-1">
                Rate ditentukan berdasarkan jenjang kelas dan rentang jumlah siswa. Pastikan rentang antar rate tidak tumpang tindih.
            </p>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Jenjang</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Rentang Siswa</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Rate / Sesi</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($rates as $rate)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $rate->jenjang }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $rate->min_siswa }}–{{ $rate->max_siswa }} siswa</td>
                                    <td class="px-4 py-3 text-gray-600">Rp{{ number_format($rate->rate_per_sesi, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('rate-gaji.edit', $rate) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form action="{{ route('rate-gaji.destroy', $rate) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rate ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada rate gaji.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
