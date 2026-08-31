@php
    $rate = $rate ?? null;
    $modalName = $modalName ?? null;
    $aktif = $modalName !== null && old('_modal') === $modalName;
    $nilai = fn (string $field, $default = '') => $aktif ? old($field, $default) : $default;
    $galat = fn (string $field) => $aktif ? $errors->first($field) : null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Jenjang</label>
    <select name="jenjang" class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @foreach (['SD', 'SMP', 'SMA'] as $value)
            <option value="{{ $value }}" @selected($nilai('jenjang', $rate->jenjang ?? '') === $value)>{{ $value }}</option>
        @endforeach
    </select>
    @if ($pesan = $galat('jenjang')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Min Siswa</label>
        <input type="number" min="0" name="min_siswa" value="{{ $nilai('min_siswa', $rate->min_siswa ?? '') }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('min_siswa')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Max Siswa</label>
        <input type="number" min="0" name="max_siswa" value="{{ $nilai('max_siswa', $rate->max_siswa ?? '') }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm">
        @if ($pesan = $galat('max_siswa')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Rate per Sesi (Rp)</label>
    <input type="number" min="0" name="rate_per_sesi" value="{{ $nilai('rate_per_sesi', $rate->rate_per_sesi ?? '') }}" required placeholder="50000"
        class="mt-1.5 block w-full border-gray-300 shadow-sm">
    @if ($pesan = $galat('rate_per_sesi')) <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p> @endif
</div>
