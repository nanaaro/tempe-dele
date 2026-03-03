<nav class="border-gray-200 bg-white py-6 dark:border-gray-700 dark:bg-gray-900">
  <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between px-1">
    <a href="#" class="flex items-center">
      <img src="../images/logo.png" alt="Logo OverTime" class="w-16 md:w-16 lg:w-12 h-auto" />
      <div class="flex flex-col leading-tight">
        <span class="text-bs font-semibold">
            TEMPE DELE
        </span>
        <span class="text-sm text-gray-500">
            Sistem Pengelolaan Dokumen Lembur
        </span>
       </div>
    </a>

    <div class="flex items-center lg:order-2">

    <!-- Profile Dropdown -->
    <div class="relative group">

        <!-- Trigger -->
        <div class="flex items-center gap-2 cursor-pointer text-gray-700">
            <svg class="h-9 w-9"
                 xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 640 640"
                 fill="currentColor">
                <path d="M463 448.2C440.9 409.8 399.4 384 352 384L288 384C240.6 384 199.1 409.8
                177 448.2C212.2 487.4 263.2 512 320 512C376.8 512 427.8 487.3 463 448.2zM64 320C64
                178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6
                576 64 461.4 64 320zM320 336C359.8 336 392 303.8 392 264C392 224.2 359.8 192 320
                192C280.2 192 248 224.2 248 264C248 303.8 280.2 336 320 336z"/>
            </svg>

            <span class="hidden sm:block text-bs font-medium">
                Hi, {{ Auth::user()->name ?? 'User' }}
            </span>
        </div>

        <!-- Dropdown -->
        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100
                    absolute right-0 mt-1 w-40 rounded-xl bg-white shadow-lg
                    ring-1 ring-gray-200 transition-all duration-200 ml-30">

            <div class="px-4 py-2 border-b border-gray-200">
                <a href="{{ route('profile') }}">
                    <p class="text-sm font-semibold text-gray-900">
                        Nama Lengkap
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        user@email.com
                    </p>
                </a>
            </div>

            <div class="py-2 text-sm">
                <a href="{{route('presensi')}}"
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                    Presensi
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hamburger (MOBILE ONLY) -->
      <button
        type="button"
        class="ml-2 inline-flex items-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 lg:hidden"
        aria-controls="mobile-menu-2"
        aria-expanded="false"
        data-collapse-toggle="mobile-menu-2">
        <span class="sr-only">Open main menu</span>

        <!-- hamburger -->
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd"
            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
            clip-rule="evenodd"></path>
        </svg>
      </button>
    </div>

    <div class="hidden w-full items-center justify-between lg:order-1 lg:flex lg:w-auto" id="mobile-menu-2">
        <ul class="mt-4 flex flex-col font-medium lg:mt-0 lg:flex-row lg:space-x-8">
        @php
            $menus = [
                'Beranda' => 'dashboard',
                'Lembur' => 'lembur',
                'Akumulasi' => 'akumulasi',
                'Rekapitulasi' => 'rekapitulasi'];
        @endphp

        @foreach ($menus as $label => $route)
            <li>
                <a href="{{ route($route) }}"
                    class="block border-b border-gray-100 py-2 pl-3 pr-4 lg:border-0 lg:p-0
                    {{ request()->routeIs($route)
                            ? 'text-[#faa938] font-semibold'
                            : 'text-gray-700 hover:text-[#faa938]' }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
      </ul>
    </div>
  </div>
</nav>
