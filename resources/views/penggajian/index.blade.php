@php
    $paramPeriode = collect([
        'mode' => $periode['mode'],
        'bulan' => $periode['bulan'],
        'tahun' => $periode['tahun'],
        'dari_bulan' => $periode['dari_bulan'],
        'dari_tahun' => $periode['dari_tahun'],
        'sampai_bulan' => $periode['sampai_bulan'],
        'sampai_tahun' => $periode['sampai_tahun'],
        'semester_id' => $periode['semester_id'],
    ])->filter(fn ($v) => $v !== null)->all();

    $opsiTahun = range(now()->year - 2, now()->year + 1);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 sm:gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap Penggajian</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $periode['label'] }}</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('semesters.index') }}" class="text-sm text-indigo-600 hover:underline">Atur Semester</a>
                <a href="{{ route('rate-gaji.index') }}" class="text-sm text-indigo-600 hover:underline">Atur Rate Gaji</a>
            </div>
        </div>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            <form method="GET" action="{{ route('penggajian.index') }}"
                x-data="{ mode: '{{ $periode['mode'] }}' }"
                class="mb-4 bg-white border border-gray-100 rounded-xl p-4">

                <div class="flex flex-wrap items-end gap-3">
                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Periode</label>
                        <select name="mode" x-model="mode" class="border-gray-300 shadow-sm">
                            <option value="bulan">Per bulan</option>
                            <option value="rentang">Rentang bulan</option>
                            <option value="semester">Per semester</option>
                        </select>
                    </div>

                    {{-- Per bulan --}}
                    <template x-if="mode === 'bulan'">
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="w-40">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                                <select name="bulan" class="border-gray-300 shadow-sm">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" @selected($m == $periode['bulan'])>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-32">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                                <select name="tahun" class="border-gray-300 shadow-sm">
                                    @foreach ($opsiTahun as $y)
                                        <option value="{{ $y }}" @selected($y == $periode['tahun'])>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </template>

                    {{-- Rentang bulan --}}
                    <template x-if="mode === 'rentang'">
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="w-36">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Dari bulan</label>
                                <select name="dari_bulan" class="border-gray-300 shadow-sm">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" @selected($m == $periode['dari_bulan'])>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-28">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                                <select name="dari_tahun" class="border-gray-300 shadow-sm">
                                    @foreach ($opsiTahun as $y)
                                        <option value="{{ $y }}" @selected($y == $periode['dari_tahun'])>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-36">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai bulan</label>
                                <select name="sampai_bulan" class="border-gray-300 shadow-sm">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" @selected($m == $periode['sampai_bulan'])>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-28">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                                <select name="sampai_tahun" class="border-gray-300 shadow-sm">
                                    @foreach ($opsiTahun as $y)
                                        <option value="{{ $y }}" @selected($y == $periode['sampai_tahun'])>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </template>

                    {{-- Per semester --}}
                    <template x-if="mode === 'semester'">
                        <div class="w-72">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Semester</label>
                            @if ($semesters->isEmpty())
                                <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                                    Belum ada data semester.
                                    <a href="{{ route('semesters.index') }}" class="font-medium underline">Tambahkan dulu</a>
                                </p>
                            @else
                                <select name="semester_id" class="border-gray-300 shadow-sm">
                                    @foreach ($semesters as $s)
                                        <option value="{{ $s->id }}" @selected($s->id == $periode['semester_id'])>
                                            {{ $s->label }} ({{ $s->tanggal_mulai->translatedFormat('d M Y') }} – {{ $s->tanggal_selesai->translatedFormat('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </template>

                    <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700">Tampilkan</button>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    {{ $periode['mulai']->translatedFormat('d M Y') }} – {{ $periode['selesai']->translatedFormat('d M Y') }}
                    · Tabungan dipotong {{ $persenTabungan }}% dari total gaji.
                </p>
            </form>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Total sesi hadir</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">{{ $totalSesi }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Total gaji</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">Rp{{ number_format($totalGaji, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Total tabungan</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">Rp{{ number_format($totalTabungan, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5" style="background:#F1F5FF;">
                    <div class="text-xs text-gray-600 mb-1">Total dikeluarkan</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">Rp{{ number_format($totalDiterima, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Guru</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Total sesi hadir</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Total gaji</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Tabungan ({{ $persenTabungan }}%)</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Gaji diterima</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekap as $guru)
                                <tr class="border-b border-gray-100">
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $guru->nama }}</td>
                                    <td class="px-4 py-2.5 text-right text-gray-600 tabular-nums">{{ $guru->total_sesi_hadir }}</td>
                                    <td class="px-4 py-2.5 text-right text-gray-900 tabular-nums">Rp{{ number_format($guru->total_gaji ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-right text-gray-500 tabular-nums">Rp{{ number_format($guru->tabungan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-right font-semibold text-gray-900 tabular-nums">Rp{{ number_format($guru->gaji_diterima, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center justify-end whitespace-nowrap">
                                            <x-action-button variant="utama" :href="route('penggajian.detail', ['guru' => $guru->id] + $paramPeriode)">Detail</x-action-button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($rekap->isNotEmpty())
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-gray-700">Total</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-700 tabular-nums">{{ $totalSesi }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900 tabular-nums">Rp{{ number_format($totalGaji, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-700 tabular-nums">Rp{{ number_format($totalTabungan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900 tabular-nums">Rp{{ number_format($totalDiterima, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
