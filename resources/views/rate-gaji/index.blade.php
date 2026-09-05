<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rate Gaji per Sesi</h2>
            <button type="button" x-data x-on:click="$dispatch('open-modal', 'tambah-rate')"
                class="inline-flex items-center gap-1.5 shrink-0 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 whitespace-nowrap">
                + Tambah Rate
            </button>
        </div>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div>
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <p class="text-sm text-gray-500 mb-4">
                Rate ditentukan berdasarkan jenjang kelas dan rentang jumlah siswa. Pastikan rentang antar rate tidak tumpang tindih.
            </p>

            <form action="{{ route('rate-gaji.pengaturan') }}" method="POST" class="bg-white border border-gray-100 rounded-xl p-4 mb-4">
                @csrf
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Nominal skenario presensi</h3>
                <p class="text-xs text-gray-500 mb-3">
                    Dipakai saat menandai presensi. Perubahan hanya berlaku untuk presensi berikutnya —
                    yang sudah tercatat tetap memakai nominal saat itu.
                </p>
                <div class="flex flex-wrap items-end gap-3">
                    <div class="w-52">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Bonus kelas gabungan (Rp)</label>
                        <input type="number" min="0" name="bonus_kelas_gabungan" value="{{ old('bonus_kelas_gabungan', $bonusGabungan) }}" required
                            class="border-gray-300 shadow-sm">
                        @error('bonus_kelas_gabungan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="w-52">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Siswa tidak masuk (Rp)</label>
                        <input type="number" min="0" name="nominal_siswa_absen" value="{{ old('nominal_siswa_absen', $nominalSiswaAbsen) }}" required
                            class="border-gray-300 shadow-sm">
                        @error('nominal_siswa_absen') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="w-52">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Potongan tabungan (%)</label>
                        <input type="number" min="0" max="100" name="persen_tabungan" value="{{ old('persen_tabungan', $persenTabungan) }}" required
                            class="border-gray-300 shadow-sm">
                        @error('persen_tabungan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                        Simpan
                    </button>
                </div>
            </form>

            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Jenjang</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500">Rentang Siswa</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500">Rate / Sesi</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rates as $rate)
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $rate->jenjang }}</td>
                                    <td class="px-4 py-2.5 text-gray-600 tabular-nums">{{ $rate->min_siswa }}–{{ $rate->max_siswa }} siswa</td>
                                    <td class="px-4 py-2.5 text-right text-gray-900 tabular-nums">Rp{{ number_format($rate->rate_per_sesi, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                            <x-action-button x-data x-on:click="$dispatch('open-modal', 'edit-rate-{{ $rate->id }}')">Edit</x-action-button>
                                            <form action="{{ route('rate-gaji.destroy', $rate) }}" method="POST" onsubmit="return confirm('Hapus rate ini?');">
                                                @csrf @method('DELETE')
                                                <x-action-button type="submit" variant="bahaya">Hapus</x-action-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada rate gaji.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-form-modal name="tambah-rate" title="Tambah Rate Gaji" :action="route('rate-gaji.store')">
        @include('rate-gaji._form', ['rate' => null, 'modalName' => 'tambah-rate'])
    </x-form-modal>

    @foreach ($rates as $rate)
        <x-form-modal :name="'edit-rate-'.$rate->id" title="Edit Rate Gaji" :action="route('rate-gaji.update', $rate)" method="PUT">
            @include('rate-gaji._form', ['rate' => $rate, 'modalName' => 'edit-rate-'.$rate->id])
        </x-form-modal>
    @endforeach
</x-app-layout>
