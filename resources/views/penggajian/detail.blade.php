<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Gaji — {{ $guru->nama }} ({{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }})
        </h2>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            <a href="{{ route('penggajian.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke rekap</a>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden mt-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Kelas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($presensis as $p)
                                <tr>
                                    <td class="px-4 py-3 text-gray-900">{{ $p->tanggal->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $p->jadwal->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-900">Rp{{ number_format($p->nominal_gaji ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">Tidak ada sesi hadir pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">Rp{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
