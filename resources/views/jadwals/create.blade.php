<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Jadwal</h2>
    </x-slot>

    <div class="py-5 px-5 lg:px-7">
        <div class="max-w-2xl">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('jadwals.store') }}" method="POST" class="space-y-4">
                    @csrf
                    @include('jadwals._form')
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('jadwals.index') }}" class="px-4 py-2 text-sm text-gray-600">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
