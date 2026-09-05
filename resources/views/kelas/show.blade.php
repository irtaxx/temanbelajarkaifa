@php
    $guruPengajar = $kelas->jadwals->pluck('guru')->unique('id')->sortBy('nama');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
            <div class="min-w-0">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $kelas->nama_kelas }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $kelas->jenjang }} · Semester {{ $kelas->semester }} · Tahun Ajar {{ $kelas->tahun_ajar }}
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" x-data x-on:click="$dispatch('open-modal', 'edit-kelas-{{ $kelas->id }}')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                    Edit Kelas
                </button>
                <button type="button" x-data x-on:click="$dispatch('open-modal', 'tambah-jadwal-kelas')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 whitespace-nowrap">
                    + Tambah Jadwal
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            <a href="{{ route('kelas.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke daftar kelas</a>

            @if (session('status'))
                <div class="mt-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4 mb-4">
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Jumlah siswa</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">{{ $kelas->jumlah_siswa }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Jumlah jadwal</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">{{ $kelas->jadwals->count() }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Guru pengajar</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">{{ $guruPengajar->count() }}</div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-3.5">
                    <div class="text-xs text-gray-500 mb-1">Rate per sesi</div>
                    <div class="font-display font-bold text-xl text-gray-900 tabular-nums">
                        {{ $rate ? 'Rp'.number_format($rate->rate_per_sesi, 0, ',', '.') : '—' }}
                    </div>
                </div>
            </div>

            @unless ($rate)
                <div class="mb-4 px-4 py-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                    Belum ada rate gaji untuk jenjang {{ $kelas->jenjang }} dengan {{ $kelas->jumlah_siswa }} siswa,
                    jadi presensi kelas ini akan bernilai Rp0.
                    <a href="{{ route('rate-gaji.index') }}" class="font-medium underline">Atur rate</a>
                </div>
            @endunless

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <div class="px-4 pt-4 pb-3">
                    <h3 class="text-sm font-semibold text-gray-800">Jadwal &amp; guru pengajar</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Semua sesi yang terdaftar untuk kelas ini.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-t border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Hari</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Jam</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Guru</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Mapel</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas->jadwals as $j)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5 text-gray-900">{{ $j->hari === 'Jumat' ? "Jum'at" : $j->hari }}</td>
                                    <td class="px-4 py-2.5 text-gray-600 tabular-nums">{{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $j->guru->nama }}</td>
                                    <td class="px-4 py-2.5 text-gray-600">{{ $j->mapel ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                        <form action="{{ route('jadwals.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="dari_kelas" value="{{ $kelas->id }}">
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada jadwal untuk kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-form-modal name="tambah-jadwal-kelas" title="Tambah Jadwal — {{ $kelas->nama_kelas }}" :action="route('jadwals.store')" max-width="xl">
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        <input type="hidden" name="dari_kelas" value="{{ $kelas->id }}">
        @include('jadwals._form', [
            'jadwal' => null,
            'modalName' => 'tambah-jadwal-kelas',
            'kelasTerkunci' => $kelas,
        ])
    </x-form-modal>

    <x-form-modal :name="'edit-kelas-'.$kelas->id" title="Edit Kelas" :action="route('kelas.update', $kelas)" method="PUT">
        @include('kelas._form', ['kelas' => $kelas, 'modalName' => 'edit-kelas-'.$kelas->id])
    </x-form-modal>
</x-app-layout>
