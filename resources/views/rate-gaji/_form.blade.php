@php $rate = $rate ?? null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Jenjang</label>
    <select name="jenjang" class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @foreach (['SD', 'SMP', 'SMA'] as $value)
            <option value="{{ $value }}" @selected(old('jenjang', $rate->jenjang ?? '') === $value)>{{ $value }}</option>
        @endforeach
    </select>
    @error('jenjang') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Min Siswa</label>
        <input type="number" min="0" name="min_siswa" value="{{ old('min_siswa', $rate->min_siswa ?? '') }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('min_siswa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Max Siswa</label>
        <input type="number" min="0" name="max_siswa" value="{{ old('max_siswa', $rate->max_siswa ?? '') }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('max_siswa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Rate per Sesi (Rp)</label>
    <input type="number" min="0" name="rate_per_sesi" value="{{ old('rate_per_sesi', $rate->rate_per_sesi ?? '') }}" required placeholder="50000"
        class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('rate_per_sesi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
