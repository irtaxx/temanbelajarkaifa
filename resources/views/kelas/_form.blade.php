@php
    $kelas = $kelas ?? null;
    $modalName = $modalName ?? null;
    $aktif = $modalName !== null && old('_modal') === $modalName;
    $nilai = fn (string $field, $default = '') => $aktif ? old($field, $default) : $default;
    $galat = fn (string $field) => $aktif ? $errors->first($field) : null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
    <input type="text" name="nama_kelas" value="{{ $nilai('nama_kelas', $kelas->nama_kelas ?? '') }}" required placeholder="mis. SD Kelas 3 - Pagi"
        class="mt-1.5 block w-full border-gray-300 shadow-sm">
    @if ($pesan = $galat('nama_kelas')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Jenjang</label>
        <select name="jenjang" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach (['SD', 'SMP', 'SMA'] as $value)
                <option value="{{ $value }}" @selected($nilai('jenjang', $kelas->jenjang ?? '') === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('jenjang')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Jumlah Siswa</label>
        <input type="number" min="0" name="jumlah_siswa" value="{{ $nilai('jumlah_siswa', $kelas->jumlah_siswa ?? 0) }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('jumlah_siswa')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Semester</label>
        <select name="semester" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach (['Ganjil', 'Genap'] as $value)
                <option value="{{ $value }}" @selected($nilai('semester', $kelas->semester ?? 'Ganjil') === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('semester')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tahun Ajar</label>
        <select name="tahun_ajar" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach (\App\Models\Kelas::opsiTahunAjar() as $value)
                <option value="{{ $value }}" @selected($nilai('tahun_ajar', $kelas->tahun_ajar ?? \App\Models\Kelas::tahunAjarBerjalan()) === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('tahun_ajar')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>
