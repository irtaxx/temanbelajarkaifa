@props(['class' => 'h-14'])

@php
    $logoPath = public_path('images/logo-kaifa.png');
@endphp

@if (file_exists($logoPath))
    <img src="{{ asset('images/logo-kaifa.png') }}" alt="Teman Belajar Kaifa" class="{{ $class }} w-auto">
@else
    {{-- Fallback selama file logo belum diunggah ke public/images/logo-kaifa.png --}}
    <div class="flex items-center gap-2.5">
        <x-application-logo class="w-11 h-11" />
        <div>
            <p class="text-[11px] leading-none text-gray-400">Teman Belajar</p>
            <p class="text-xl leading-tight font-extrabold text-gray-800" style="font-family:'Plus Jakarta Sans';">Kaifa</p>
        </div>
    </div>
@endif
