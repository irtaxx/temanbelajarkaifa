@props(['name'])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes }}>
    @switch($name)
        @case('dash')
            <rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.5" />
            <rect x="13" y="3.5" width="7.5" height="7.5" rx="1.5" />
            <rect x="3.5" y="13" width="7.5" height="7.5" rx="1.5" />
            <rect x="13" y="13" width="7.5" height="7.5" rx="1.5" />
            @break
        @case('check')
            <circle cx="12" cy="12" r="8.5" />
            <path d="M8.5 12.3l2.4 2.4 4.6-5" />
            @break
        @case('cal')
            <rect x="3.5" y="5" width="17" height="15" rx="2" />
            <path d="M3.5 9.5h17M8 3v4M16 3v4" />
            @break
        @case('user')
            <circle cx="12" cy="8.2" r="3.7" />
            <path d="M4.8 20c1-3.6 3.9-5.7 7.2-5.7s6.2 2.1 7.2 5.7" />
            @break
        @case('book')
            <path d="M4 5.2c2.6-1 5-1 8 .3 3-1.3 5.4-1.3 8-.3v13.6c-2.6-1-5-1-8 .3-3-1.3-5.4-1.3-8-.3V5.2z" />
            <path d="M12 5.5v13.6" />
            @break
        @case('wallet')
            <rect x="3.5" y="6.5" width="17" height="12" rx="2.2" />
            <path d="M3.5 10.3h17" />
            <circle cx="16.3" cy="14.2" r="1.1" fill="currentColor" stroke="none" />
            @break
        @case('logout')
            <path d="M9 20H5.5A1.5 1.5 0 0 1 4 18.5v-13A1.5 1.5 0 0 1 5.5 4H9" />
            <path d="M15.5 16l4-4-4-4M19 12H9" />
            @break
    @endswitch
</svg>
