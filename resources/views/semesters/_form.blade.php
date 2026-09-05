@php
    $semester = $semester ?? null;
    $modalName = $modalName ?? null;
    $aktif = $modalName !== null && old('_modal') === $modalName;
    $nilai = fn (string $field, $default = '') => $aktif ? old($field, $default) : $default;
    $galat = fn (string $field) => $aktif ? $errors->first($field) : null;
@endphp

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Semester</label>
        <select name="nama" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach (['Ganjil', 'Genap'] as $value)
                <option value="{{ $value }}" @selected($nilai('nama', $semester->nama ?? 'Ganjil') === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('nama')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tahun Ajar</label>
        <select name="tahun_ajar" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach ($opsiTahunAjar as $value)
                <option value="{{ $value }}" @selected($nilai('tahun_ajar', $semester->tahun_ajar ?? \App\Models\Kelas::tahunAjarBerjalan()) === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('tahun_ajar')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" required
            value="{{ $nilai('tanggal_mulai', $semester?->tanggal_mulai?->toDateString() ?? '') }}"
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('tanggal_mulai')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" required
            value="{{ $nilai('tanggal_selesai', $semester?->tanggal_selesai?->toDateString() ?? '') }}"
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('tanggal_selesai')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>

<p class="text-xs text-gray-500">
    Rentang tanggal ini dipakai untuk opsi "Per semester" pada rekap penggajian.
</p>
