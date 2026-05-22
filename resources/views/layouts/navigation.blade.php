<nav x-data="{ open: false }" class="nav-bar sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="flex justify-between items-center h-[4.25rem]">
            <div class="flex items-center gap-8 lg:gap-12">
                <x-brand-logo variant="light" size="sm" :href="route('dashboard')" />

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="nav-pill {{ request()->routeIs('dashboard') ? 'nav-pill-active' : '' }}">Home</a>
                    <a href="{{ route('shifts.available') }}" class="nav-pill {{ request()->routeIs('shifts.available') ? 'nav-pill-active' : '' }}">Open shifts</a>
                    <a href="{{ route('shifts.mine') }}" class="nav-pill {{ request()->routeIs('shifts.mine') ? 'nav-pill-active' : '' }}">My schedule</a>
                    <a href="{{ route('team.index') }}" class="nav-pill {{ request()->routeIs('team.index') ? 'nav-pill-active' : '' }}">Staff</a>
                    @if (Auth::user()->isManager())
                        <a href="{{ route('manager.shifts') }}" class="nav-pill {{ request()->routeIs('manager.shifts') ? 'nav-pill-active' : '' }}">Manage rota</a>
                    @endif
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="avatar text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <x-role-badge :role="Auth::user()->role" class="mt-0.5" />
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="nav-pill text-xs">Log out</button>
                </form>
            </div>

            <button @click="open = !open" class="md:hidden p-2 text-amber-100/80 hover:text-white">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': !open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="md:hidden border-t border-white/10 px-5 py-4 space-y-1" style="background: rgba(26,15,10,0.98)">
        <a href="{{ route('dashboard') }}" class="block nav-pill {{ request()->routeIs('dashboard') ? 'nav-pill-active' : '' }}">Home</a>
        <a href="{{ route('shifts.available') }}" class="block nav-pill {{ request()->routeIs('shifts.available') ? 'nav-pill-active' : '' }}">Open shifts</a>
        <a href="{{ route('shifts.mine') }}" class="block nav-pill {{ request()->routeIs('shifts.mine') ? 'nav-pill-active' : '' }}">My schedule</a>
        <a href="{{ route('team.index') }}" class="block nav-pill {{ request()->routeIs('team.index') ? 'nav-pill-active' : '' }}">Staff</a>
        @if (Auth::user()->isManager())
            <a href="{{ route('manager.shifts') }}" class="block nav-pill {{ request()->routeIs('manager.shifts') ? 'nav-pill-active' : '' }}">Manage rota</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="pt-2">
            @csrf
            <button type="submit" class="nav-pill w-full text-left">Log out</button>
        </form>
    </div>
</nav>
