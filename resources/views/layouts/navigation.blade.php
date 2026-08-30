@php
    $navGroups = [
        'Utama' => [
            ['route' => 'dashboard', 'pattern' => ['dashboard'], 'label' => 'Dashboard', 'icon' => 'dash', 'color' => '#5B7BE0'],
            ['route' => 'presensi.index', 'pattern' => ['presensi.*'], 'label' => 'Presensi', 'icon' => 'check', 'color' => '#E24B3A'],
        ],
        'Operasional' => [
            ['route' => 'jadwals.index', 'pattern' => ['jadwals.*'], 'label' => 'Jadwal', 'icon' => 'cal', 'color' => '#EF8A1E'],
            ['route' => 'gurus.index', 'pattern' => ['gurus.*'], 'label' => 'Guru', 'icon' => 'user', 'color' => '#2AA152'],
            ['route' => 'kelas.index', 'pattern' => ['kelas.*'], 'label' => 'Kelas', 'icon' => 'book', 'color' => '#28A9D6'],
        ],
        'Keuangan' => [
            ['route' => 'penggajian.index', 'pattern' => ['penggajian.*', 'rate-gaji.*'], 'label' => 'Penggajian', 'icon' => 'wallet', 'color' => '#C77CD1'],
        ],
    ];
    $mobileItems = collect($navGroups)->flatten(1)->reject(fn ($item) => $item['route'] === 'dashboard');
@endphp

{{-- Desktop sidebar --}}
<aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:w-60 lg:px-3 lg:py-5" style="background:#211E19;">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 pb-5">
        <x-application-logo class="w-7 h-7 shrink-0" />
        <span class="font-semibold text-[15px] tracking-tight" style="color:#F3EEE5;">Kaifa</span>
    </a>

    <style>
        .nav-link:hover:not(.nav-link-active) { background: #272319; }
    </style>

    <nav class="flex flex-col flex-1">
        @foreach ($navGroups as $groupLabel => $items)
            <p class="px-3 pt-3 pb-1.5 text-[10px] font-semibold uppercase tracking-wider" style="color:#6B665A;">{{ $groupLabel }}</p>
            <div class="flex flex-col gap-1 mb-1">
                @foreach ($items as $item)
                    @php $active = request()->routeIs(...$item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13.5px] font-medium border-l-[2.5px] transition {{ $active ? 'nav-link-active' : '' }}"
                        style="{{ $active ? 'background:#2C2822;color:#F3EEE5;border-left-color:'.$item['color'].';' : 'color:#9A907F;border-left-color:transparent;' }}"
                    >
                        <x-nav-icon :name="$item['icon']" class="w-[17px] h-[17px] shrink-0" style="color:{{ $item['color'] }}" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="pt-3 mt-2 flex items-center gap-2.5" style="border-top:0.5px solid #332E26;">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 flex-1 min-w-0 px-1">
            <span class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold shrink-0" style="background:#332E26;color:#D8CFBE;">
                {{ Str::of(auth()->user()->name)->substr(0, 1)->upper() }}
            </span>
            <span class="text-[12px] truncate" style="color:#B0A794;">{{ auth()->user()->name }}</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="p-1.5 rounded-md" style="color:#8A8272;" title="Keluar">
                <x-nav-icon name="logout" class="w-4 h-4" />
            </button>
        </form>
    </div>
</aside>

{{-- Mobile top bar --}}
<div class="lg:hidden sticky top-0 z-30 flex items-center justify-between px-4 py-3" style="background:#211E19;">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <x-application-logo class="w-6 h-6 shrink-0" />
        <span class="font-semibold text-[14px]" style="color:#F3EEE5;">Kaifa</span>
    </a>
    <div class="flex items-center gap-1">
        <a href="{{ route('profile.edit') }}" class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold" style="background:#332E26;color:#D8CFBE;">
            {{ Str::of(auth()->user()->name)->substr(0, 1)->upper() }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="p-1.5 rounded-md" style="color:#8A8272;" title="Keluar">
                <x-nav-icon name="logout" class="w-[18px] h-[18px]" />
            </button>
        </form>
    </div>
</div>

{{-- Mobile bottom nav --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 flex items-stretch justify-around" style="background:#211E19; padding-bottom:env(safe-area-inset-bottom);">
    @foreach ($mobileItems as $item)
        @php $active = request()->routeIs(...$item['pattern']); @endphp
        <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center gap-1 py-2.5 flex-1 text-[9.5px] font-semibold"
            style="color:{{ $active ? '#F3EEE5' : '#8A8272' }};">
            <x-nav-icon :name="$item['icon']" class="w-[19px] h-[19px]" style="color:{{ $item['color'] }}" />
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
