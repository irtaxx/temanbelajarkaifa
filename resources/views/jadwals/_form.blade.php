@php $jadwal = $jadwal ?? null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Guru</label>
    <select name="guru_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">-- Pilih Guru --</option>
        @foreach ($gurus as $g)
            <option value="{{ $g->id }}" @selected(old('guru_id', $jadwal->guru_id ?? '') == $g->id)>{{ $g->nama }}</option>
        @endforeach
    </select>
    @error('guru_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kelas</label>
    <select name="kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">-- Pilih Kelas --</option>
        @foreach ($kelas as $k)
            <option value="{{ $k->id }}" @selected(old('kelas_id', $jadwal->kelas_id ?? '') == $k->id)>{{ $k->nama_kelas }} ({{ $k->jenjang }}, {{ $k->jumlah_siswa }} siswa)</option>
        @endforeach
    </select>
    @error('kelas_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Mapel</label>
    <input type="text" name="mapel" value="{{ old('mapel', $jadwal->mapel ?? '') }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('mapel') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Hari</label>
    <select name="hari" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @foreach (['Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => "Jum'at", 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu'] as $value => $label)
            <option value="{{ $value }}" @selected(old('hari', $jadwal->hari ?? '') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('hari') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal ? substr($jadwal->jam_mulai, 0, 5) : '') }}" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('jam_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal ? substr($jadwal->jam_selesai, 0, 5) : '') }}" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('jam_selesai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
