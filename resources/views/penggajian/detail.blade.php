<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Gaji — {{ $guru->nama }} ({{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }})
        </h2>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            <a href="{{ route('penggajian.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke rekap</a>

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden mt-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Kelas</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Skenario</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presensis as $p)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5 text-gray-900">{{ $p->tanggal->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $p->jadwal->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-2.5">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-700' => $p->skenario === 'hadir',
                                            'bg-indigo-100 text-indigo-700' => $p->skenario === 'gabungan',
                                            'bg-sky-100 text-sky-700' => $p->skenario === 'siswa_absen',
                                        ])>{{ \App\Models\Presensi::SKENARIO[$p->skenario]['label'] ?? ucfirst($p->status) }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right text-gray-900 tabular-nums">Rp{{ number_format($p->nominal_gaji ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada sesi berbayar pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 tabular-nums">Rp{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
