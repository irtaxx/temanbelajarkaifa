<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap Penggajian</h2>
            <a href="{{ route('rate-gaji.index') }}" class="text-sm text-indigo-600 hover:underline">Atur Rate Gaji</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('penggajian.index') }}" class="mb-4 flex items-end gap-3 bg-white shadow-sm rounded-lg p-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select name="bulan" class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($m == $bulan)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <select name="tahun" class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">Tampilkan</button>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Guru</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Total Sesi Hadir</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Total Gaji</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($rekap as $guru)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $guru->nama }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $guru->total_sesi_hadir }}</td>
                                    <td class="px-4 py-3 text-gray-900 font-medium">Rp{{ number_format($guru->total_gaji ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('penggajian.detail', ['guru' => $guru->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="text-indigo-600 hover:underline">Detail</a>
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
        </div>
    </div>
</x-app-layout>
