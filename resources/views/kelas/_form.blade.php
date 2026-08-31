@php $kelas = $kelas ?? null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas ?? '') }}" required placeholder="mis. SD Kelas 3 - Pagi"
        class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('nama_kelas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Jenjang</label>
        <select name="jenjang" class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (['SD', 'SMP', 'SMA'] as $value)
                <option value="{{ $value }}" @selected(old('jenjang', $kelas->jenjang ?? '') === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @error('jenjang') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Jumlah Siswa</label>
        <input type="number" min="0" name="jumlah_siswa" value="{{ old('jumlah_siswa', $kelas->jumlah_siswa ?? 0) }}" required
            class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('jumlah_siswa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Semester</label>
        <select name="semester" class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (['Ganjil', 'Genap'] as $value)
                <option value="{{ $value }}" @selected(old('semester', $kelas->semester ?? 'Ganjil') === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @error('semester') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tahun Ajar</label>
        <select name="tahun_ajar" class="mt-1.5 block w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (\App\Models\Kelas::opsiTahunAjar() as $value)
                <option value="{{ $value }}" @selected(old('tahun_ajar', $kelas->tahun_ajar ?? \App\Models\Kelas::tahunAjarBerjalan()) === $value)>{{ $value }}</option>
            @endforeach
        </select>
        @error('tahun_ajar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
