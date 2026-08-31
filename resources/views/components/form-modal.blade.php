@props([
    'name',
    'title',
    'action',
    'method' => 'POST',
    'submitLabel' => 'Simpan',
    'maxWidth' => 'lg',
])

@php
    $widthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth];

    // Modal dibuka ulang otomatis kalau validasi gagal, supaya isian tidak hilang.
    $reopen = $errors->any() && old('_modal') === $name;
@endphp

<div
    x-data="{ show: {{ $reopen ? 'true' : 'false' }} }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
>
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50"
        x-on:click="show = false"
    ></div>

    <div class="relative min-h-full flex items-start sm:items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
            class="relative w-full {{ $widthClass }} bg-white rounded-2xl shadow-xl overflow-hidden"
        >
            <div class="flex items-start justify-between px-5 pt-4 pb-3 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900 font-display">{{ $title }}</h3>
                <button type="button" x-on:click="show = false" class="p-1 -m-1 text-gray-400 hover:text-gray-600" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="w-5 h-5">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <form action="{{ $action }}" method="POST">
                @csrf
                @if (strtoupper($method) !== 'POST')
                    @method($method)
                @endif
                <input type="hidden" name="_modal" value="{{ $name }}">

                <div class="px-5 py-4 space-y-4 max-h-[70vh] overflow-y-auto">
                    {{ $slot }}
                </div>

                <div class="flex justify-end gap-2 px-5 py-3 bg-gray-50 border-t border-gray-100">
                    <button type="button" x-on:click="show = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                        {{ $submitLabel }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
