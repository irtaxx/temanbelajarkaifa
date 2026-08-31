@php
    $jadwal = $jadwal ?? null;
    $modalName = $modalName ?? null;
    $aktif = $modalName !== null && old('_modal') === $modalName;
    $nilai = fn (string $field, $default = '') => $aktif ? old($field, $default) : $default;
    $galat = fn (string $field) => $aktif ? $errors->first($field) : null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Guru</label>
    <select name="guru_id" class="mt-1.5 block w-full border-gray-300 shadow-sm">
        <option value="">-- Pilih Guru --</option>
        @foreach ($gurus as $g)
            <option value="{{ $g->id }}" @selected($nilai('guru_id', $jadwal->guru_id ?? '') == $g->id)>{{ $g->nama }}</option>
        @endforeach
    </select>
    @if ($pesan = $galat('guru_id')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kelas</label>
    <select name="kelas_id" class="mt-1.5 block w-full border-gray-300 shadow-sm">
        <option value="">-- Pilih Kelas --</option>
        @foreach ($kelas as $k)
            <option value="{{ $k->id }}" @selected($nilai('kelas_id', $jadwal->kelas_id ?? '') == $k->id)>{{ $k->nama_kelas }} ({{ $k->jenjang }} · {{ $k->semester }} {{ $k->tahun_ajar }} · {{ $k->jumlah_siswa }} siswa)</option>
        @endforeach
    </select>
    @if ($pesan = $galat('kelas_id')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Mapel</label>
        <input type="text" name="mapel" value="{{ $nilai('mapel', $jadwal->mapel ?? '') }}" placeholder="mis. Matematika"
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('mapel')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Hari</label>
        <select name="hari" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach (['Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => "Jum'at", 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu'] as $value => $label)
                <option value="{{ $value }}" @selected($nilai('hari', $jadwal->hari ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('hari')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
        <input type="time" name="jam_mulai" value="{{ $nilai('jam_mulai', $jadwal ? substr($jadwal->jam_mulai, 0, 5) : '') }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('jam_mulai')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
        <input type="time" name="jam_selesai" value="{{ $nilai('jam_selesai', $jadwal ? substr($jadwal->jam_selesai, 0, 5) : '') }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('jam_selesai')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>
