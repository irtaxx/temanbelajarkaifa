@props([
    'variant' => 'netral',
    'href' => null,
])

@php
    $gaya = [
        'netral' => 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400',
        'utama' => 'border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100',
        'bahaya' => 'border-red-200 text-red-700 bg-white hover:bg-red-50 hover:border-red-300',
    ][$variant] ?? 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50';

    $kelas = 'inline-flex items-center justify-center px-2.5 py-1.5 text-xs font-medium border rounded-lg transition '.$gaya;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $kelas]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $kelas]) }}>{{ $slot }}</button>
@endif
