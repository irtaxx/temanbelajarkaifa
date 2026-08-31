@php
    $markPath = public_path('images/logo-kaifa-mark.png');
    $petals = [
        ['angle' => 0, 'color' => '#E24B3A'],
        ['angle' => 45, 'color' => '#EF8A1E'],
        ['angle' => 90, 'color' => '#F2BC2B'],
        ['angle' => 135, 'color' => '#2AA152'],
        ['angle' => 180, 'color' => '#28A9D6'],
        ['angle' => 225, 'color' => '#2C3E8C'],
        ['angle' => 270, 'color' => '#8E3E96'],
        ['angle' => 315, 'color' => '#E33F8C'],
    ];
@endphp

@if (file_exists($markPath))
    <img src="{{ asset('images/logo-kaifa-mark.png') }}" alt="Kaifa" {{ $attributes }}>
@else
    {{-- Fallback bila file mark belum ada --}}
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
        @foreach ($petals as $petal)
            <g transform="rotate({{ $petal['angle'] }} 50 50)">
                <rect x="46.5" y="10" width="7" height="26" rx="3.5" fill="{{ $petal['color'] }}" />
                <circle cx="50" cy="5" r="3.4" fill="{{ $petal['color'] }}" />
            </g>
        @endforeach
    </svg>
@endif
