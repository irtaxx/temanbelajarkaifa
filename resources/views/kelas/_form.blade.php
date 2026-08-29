@php $kelas = $kelas ?? null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas ?? '') }}" required placeholder="mis. SD Kelas 3 - Pagi"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('nama_kelas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Jenjang</label>
    <select name="jenjang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @foreach (['SD', 'SMP', 'SMA'] as $value)
            <option value="{{ $value }}" @selected(old('jenjang', $kelas->jenjang ?? '') === $value)>{{ $value }}</option>
        @endforeach
    </select>
    @error('jenjang') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Jumlah Siswa</label>
    <input type="number" min="0" name="jumlah_siswa" value="{{ old('jumlah_siswa', $kelas->jumlah_siswa ?? 0) }}" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('jumlah_siswa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
