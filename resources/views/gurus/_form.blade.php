@php
    $guru = $guru ?? null;
    // Satu halaman memuat banyak modal (tambah + edit tiap baris). Nilai old() hanya
    // dipakai oleh modal yang gagal validasi, supaya modal lain tidak ikut terisi ulang.
    $modalName = $modalName ?? null;
    $aktif = $modalName !== null && old('_modal') === $modalName;
    $nilai = fn (string $field, $default = '') => $aktif ? old($field, $default) : $default;
    $galat = fn (string $field) => $aktif ? $errors->first($field) : null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama</label>
    <input type="text" name="nama" value="{{ $nilai('nama', $guru->nama ?? '') }}" required
        class="mt-1.5 block w-full border-gray-300 shadow-sm">
    @if ($pesan = $galat('nama')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">No. HP</label>
        <input type="text" name="no_hp" value="{{ $nilai('no_hp', $guru->no_hp ?? '') }}" placeholder="0812xxxxxxx"
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('no_hp')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1.5 block w-full border-gray-300 shadow-sm">
            @foreach (['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $value => $label)
                <option value="{{ $value }}" @selected($nilai('status', $guru->status ?? 'aktif') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if ($pesan = $galat('status')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Alamat</label>
    <textarea name="alamat" rows="2" class="mt-1.5 block w-full border-gray-300 shadow-sm">{{ $nilai('alamat', $guru->alamat ?? '') }}</textarea>
    @if ($pesan = $galat('alamat')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>
