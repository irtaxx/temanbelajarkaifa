<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">Selamat datang kembali, {{ auth()->user()->name }}.</p>
            </div>
            <a href="{{ route('presensi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                <x-nav-icon name="check" class="w-4 h-4" />
                Presensi hari ini
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500">Sesi hari ini</span>
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#FDEBE8;color:#E24B3A;">
                            <x-nav-icon name="check" class="w-3.5 h-3.5" />
                        </span>
                    </div>
                    <div class="font-display font-bold text-xl text-gray-900">{{ $sesiHariIni }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500">Guru aktif</span>
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#E1F3E6;color:#2AA152;">
                            <x-nav-icon name="user" class="w-3.5 h-3.5" />
                        </span>
                    </div>
                    <div class="font-display font-bold text-xl text-gray-900">{{ $guruAktif }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500">Gaji bulan ini</span>
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#F1E5F2;color:#8E3E96;">
                            <x-nav-icon name="wallet" class="w-3.5 h-3.5" />
                        </span>
                    </div>
                    <div class="font-display font-bold text-xl text-gray-900">Rp{{ number_format($gajiBulanIni, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500">Kehadiran bulan ini</span>
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#E1F0F7;color:#0F5C78;">
                            <x-nav-icon name="book" class="w-3.5 h-3.5" />
                        </span>
                    </div>
                    <div class="font-display font-bold text-xl text-gray-900">{{ $persenKehadiran }}%</div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <div class="flex justify-between items-center px-4 pt-3 pb-2.5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Presensi terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Yang baru saja ditandai</p>
                    </div>
                    <a href="{{ route('presensi.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">Lihat semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-t border-gray-100">
                                <th class="text-left px-4 py-2 text-xs font-medium text-gray-500">Guru</th>
                                <th class="text-left px-4 py-2 text-xs font-medium text-gray-500">Kelas</th>
                                <th class="text-left px-4 py-2 text-xs font-medium text-gray-500">Status</th>
                                <th class="text-right px-4 py-2 text-xs font-medium text-gray-500">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presensiTerbaru as $p)
                                <tr class="border-t border-gray-100">
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $p->guru->nama }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $p->jadwal->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-2.5">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-700' => $p->status === 'hadir',
                                            'bg-yellow-100 text-yellow-700' => in_array($p->status, ['izin', 'sakit']),
                                            'bg-red-100 text-red-700' => $p->status === 'alpha',
                                        ])>{{ ucfirst($p->status) }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right text-gray-900">
                                        {{ $p->nominal_gaji ? 'Rp'.number_format($p->nominal_gaji, 0, ',', '.') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t border-gray-100">
                                    <td colspan="4" class="px-4 py-5 text-center text-gray-500">Belum ada presensi tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
