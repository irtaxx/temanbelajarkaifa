<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Presensi Guru</h2>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div class="max-w-4xl">
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('presensi.index') }}" class="mb-4 flex items-center gap-3 bg-white shadow-sm rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 shrink-0">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                    class="w-full rounded-md border-gray-300 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </form>

            <p class="text-sm text-gray-500 mb-4 px-1">Hari: <span class="font-medium text-gray-700">{{ $hari }}</span></p>

            <div class="space-y-4">
                @forelse ($jadwals as $j)
                    @php $presensi = $j->presensis->first(); @endphp
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-gray-900 text-base">{{ $j->guru->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $j->kelas->nama_kelas }} · {{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}</p>
                            </div>
                            @if ($presensi)
                                <span @class([
                                    'px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap',
                                    'bg-green-100 text-green-700' => $presensi->status === 'hadir',
                                    'bg-yellow-100 text-yellow-700' => in_array($presensi->status, ['izin', 'sakit']),
                                    'bg-red-100 text-red-700' => $presensi->status === 'alpha',
                                ])>{{ ucfirst($presensi->status) }}</span>
                            @endif
                        </div>

                        <form action="{{ route('presensi.store') }}" method="POST" class="grid grid-cols-4 gap-2">
                            @csrf
                            <input type="hidden" name="jadwal_id" value="{{ $j->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                            @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $value => $label)
                                <button type="submit" name="status" value="{{ $value }}"
                                    @class([
                                        'py-3 rounded-md text-sm font-semibold border-2 transition',
                                        'bg-gray-800 text-white border-gray-800' => $presensi?->status === $value,
                                        'bg-white text-gray-700 border-gray-200 active:bg-gray-100' => $presensi?->status !== $value,
                                    ])>
                                    {{ $label }}
                                </button>
                            @endforeach
                        </form>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-lg p-6 text-center text-gray-500">
                        Tidak ada jadwal mengajar pada hari {{ $hari }}.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
