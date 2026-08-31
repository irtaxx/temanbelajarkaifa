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
                                    <td class="px-4 py-2.5 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('rate-gaji.edit', $rate) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form action="{{ route('rate-gaji.destroy', $rate) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rate ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
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
        @include('rate-gaji._form', ['rate' => null])
    </x-form-modal>
</x-app-layout>
