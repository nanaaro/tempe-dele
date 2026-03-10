<nav class="h-16 bg-fff flex items-center justify-between px-6 gap-3">

    {{-- Page Title --}}
    <span class="text-xl font-bold text-slate-800 tracking-tight">
        @yield('title')
    </span>

    {{-- Right side --}}
    <div class="flex items-center gap-2">

        {{-- Notifications --}}
        <button type="button"
            class="relative flex items-center justify-center w-10 h-10 rounded-xl text-slate-400">
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-2 right-2 w-2 h-2 bg-[#faa938] rounded-full ring-2 ring-white"></span>
        </button>

        {{-- Divider --}}
        <div class="w-px h-6 bg-slate-200 mx-0.5"></div>

        {{-- Profile Dropdown --}}
        <div class="relative group z-50">

            {{-- Trigger --}}
            <button type="button"
                class="flex items-center gap-2.5 h-10 px-3 rounded-xl">
                <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 stroke-current text-[#faa938]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="hidden sm:flex flex-col leading-tight text-left">
                    <span class="text-sm font-semibold text-slate-700 truncate max-w-28">{{ Auth::user()->name ?? 'Nama User' }}</span>
                    <span class="text-xs text-slate-400 leading-none">Pegawai</span>
                </div>
                <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown --}}
            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100
                        absolute right-0 top-full mt-2 w-52 bg-white rounded-xl
                        border border-slate-200 shadow-lg shadow-slate-200/60
                        transition-all duration-150 origin-top-right scale-95 group-hover:scale-100">

                {{-- User info --}}
                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-3 border-b border-slate-100 rounded-t-xl transition-colors">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 stroke-current text-[#faa938]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col leading-tight min-w-0">
                        <span class="text-sm font-semibold text-slate-700 truncate">{{ Auth::user()->name ?? 'Nama Lengkap' }}</span>
                        <span class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'user@email.com' }}</span>
                    </div>
                </a>

                {{-- Menu items --}}
                <div class="py-2 px-1.5">
                    <a href="{{ route('presensi') }}"
                        class="flex items-center gap-2.5 w-full px-3 py-2.5 text-sm text-slate-600 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors">
                        <svg class="w-4 h-4 fill-current  text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                            <path d="M360 160L280 160C266.7 160 256 149.3 256 136C256 122.7 266.7 112 280 112L360 112C373.3 112 384 122.7 384 136C384 149.3 373.3 160 360 160zM360 208C397.1 208 427.6 180 431.6 144L448 144C456.8 144 464 151.2 464 160L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 160C176 151.2 183.2 144 192 144L208.4 144C212.4 180 242.9 208 280 208L360 208zM419.9 96C407 76.7 385 64 360 64L280 64C255 64 233 76.7 220.1 96L192 96C156.7 96 128 124.7 128 160L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 160C512 124.7 483.3 96 448 96L419.9 96z"/>
                        </svg>
                        Presensi
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2.5 w-full px-3 py-2.5 text-sm text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4 stroke-current text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Hamburger (mobile only) --}}
        <button type="button"
            class="ml-0.5 flex items-center justify-center w-10 h-10 rounded-xl text-slate-400 hover:bg-orange-50 hover:text-[#faa938] transition-colors lg:hidden"
            aria-controls="mobile-menu"
            aria-expanded="false"
            data-collapse-toggle="mobile-menu">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </div>
</nav>
