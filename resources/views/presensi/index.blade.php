<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Presensi Guru</h2>
        <p class="text-sm text-gray-500 mt-0.5">Pilih tanggal, lalu tandai sesi yang berlangsung.</p>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div class="max-w-4xl">
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('presensi.index') }}" class="mb-4 flex flex-wrap items-end gap-3 bg-white border border-gray-100 rounded-xl p-4">
                <div class="flex-1 min-w-[10rem]">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="block w-full border-gray-300 shadow-sm">
                </div>
                <div class="flex-1 min-w-[10rem]">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Guru</label>
                    <select name="guru_id" onchange="this.form.submit()" class="block w-full border-gray-300 shadow-sm">
                        <option value="">Semua guru</option>
                        @foreach ($gurus as $g)
                            <option value="{{ $g->id }}" @selected((string) $filterGuru === (string) $g->id)>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <p class="text-sm text-gray-500 mb-4">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                <span class="text-gray-400">— sesi bisa ditandai kapan saja, tidak terbatas hari terjadwalnya.</span>
            </p>

            <div class="space-y-3">
                @forelse ($jadwals as $j)
                    @php
                        $presensi = $j->presensis->first();
                        $terjadwalHariIni = $j->hari === $hari;
                    @endphp
                    <div class="bg-white border rounded-xl p-4 {{ $presensi ? 'border-gray-200' : 'border-gray-100' }}">
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900">{{ $j->guru->nama }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $j->kelas->nama_kelas }} · {{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}
                                    @if ($j->mapel) · {{ $j->mapel }} @endif
                                </p>
                                <p class="text-xs mt-1">
                                    <span @class([
                                        'px-2 py-0.5 rounded-full font-medium',
                                        'bg-indigo-50 text-indigo-700' => $terjadwalHariIni,
                                        'bg-gray-100 text-gray-500' => ! $terjadwalHariIni,
                                    ])>
                                        Jadwal {{ $j->hari === 'Jumat' ? "Jum'at" : $j->hari }}
                                    </span>
                                </p>
                            </div>
                            @if ($presensi)
                                <div class="flex items-center gap-2 shrink-0">
                                    <span @class([
                                        'px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap',
                                        'bg-green-100 text-green-700' => $presensi->status === 'hadir',
                                        'bg-yellow-100 text-yellow-700' => in_array($presensi->status, ['izin', 'sakit']),
                                        'bg-red-100 text-red-700' => $presensi->status === 'alpha',
                                    ])>{{ ucfirst($presensi->status) }}</span>
                                    <form action="{{ route('presensi.destroy', $presensi) }}" method="POST" onsubmit="return confirm('Batalkan presensi ini?');">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="guru_id" value="{{ $filterGuru }}">
                                        <button type="submit" class="text-xs text-gray-400 hover:text-red-600">Batalkan</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('presensi.store') }}" method="POST" class="grid grid-cols-4 gap-2">
                            @csrf
                            <input type="hidden" name="jadwal_id" value="{{ $j->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            <input type="hidden" name="guru_id" value="{{ $filterGuru }}">

                            @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $value => $label)
                                <button type="submit" name="status" value="{{ $value }}"
                                    @class([
                                        'py-3 rounded-lg text-sm font-semibold border-2 transition',
                                        'bg-gray-800 text-white border-gray-800' => $presensi?->status === $value,
                                        'bg-white text-gray-700 border-gray-200 hover:border-gray-300 active:bg-gray-100' => $presensi?->status !== $value,
                                    ])>
                                    {{ $label }}
                                </button>
                            @endforeach
                        </form>
                    </div>
                @empty
                    <div class="bg-white border border-gray-100 rounded-xl p-6 text-center text-gray-500">
                        Belum ada jadwal mengajar. Tambahkan dulu di menu Jadwal.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
