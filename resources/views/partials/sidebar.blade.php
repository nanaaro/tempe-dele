<body class="flex bg-slate-50 min-h-screen">

    {{-- ===================== --}}
    {{-- MINI SIDEBAR (md only) --}}
    {{-- ===================== --}}
    <aside class="hidden md:flex lg:hidden flex-col items-center w-16 min-h-screen bg-slate-900 py-5 gap-1 shrink-0">

        {{-- Logo --}}
        <a href="#" class="flex items-center justify-center w-16 h-16 mb-4">
            <img src="../images/logo.png" alt="Logo" class="w-16 h-auto" />
        </a>

        <div class="w-8 border-t border-white/10 mb-3"></div>

        {{-- Nav --}}
        @php
        $miniNav = [
            ['path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'active' => false],
            ['path' => 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'active' => true],
            ['path' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2', 'active' => false],
        ];
        @endphp

        @foreach ($miniNav as $item)
            <a href="#" class="relative flex items-center justify-center w-11 h-11 rounded-xl transition-colors
                {{ $item['active']
                    ? 'bg-[#faa938]/15 text-[#faa938]'
                    : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                @if ($item['active'])
                    <span class="absolute left-0 top-3 bottom-3 w-0.75 bg-[#faa938] rounded-full -translate-x-2.5"></span>
                @endif
                <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $item['active'] ? '2.25' : '1.75' }}" d="{{ $item['path'] }}"/>
                </svg>
            </a>
        @endforeach

        {{-- <div class="w-8 border-t border-white/10 my-3"></div>

        {{-- Presensi --}}
        {{-- <a href="#" class="relative flex items-center justify-center w-11 h-11 rounded-xl text-slate-500 hover:bg-white/5 hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                <path d="M528 320C528 434.9 434.9 528 320 528C205.1 528 112 434.9 112 320C112 205.1 205.1 112 320 112C434.9 112 528 205.1 528 320zM64 320C64 461.4 178.6 576 320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320zM296 184L296 320C296 328 300 335.5 306.7 340L402.7 404C413.7 411.4 428.6 408.4 436 397.3C443.4 386.2 440.4 371.4 429.3 364L344 307.2L344 184C344 170.7 333.3 160 320 160C306.7 160 296 170.7 296 184z"/>
            </svg>
        </a>  --}}

    </aside>

    {{-- ===================== --}}
    {{-- FULL SIDEBAR (lg+)    --}}
    {{-- ===================== --}}
    <aside class="hidden lg:flex flex-col w-64 min-h-screen bg-slate-900 py-5 shrink-0">

        {{-- Brand --}}
        <a href="#" class="flex items-center gap-2 px-4 mb-8">
            <div class="w-14 h-14 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo OverTime" class="w-full h-auto" />
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-bs font-bold text-white tracking-wide">TEMPE DELE</span>
                <span class="text-[12px] text-slate-300">
                    Sistem Pengelolaan <br>Dokumen Lembur
                </span>
            </div>
        </a>

        @php
            $role = session('user') ? \DB::table('m_pegawai')->where('nip', session('user')['nip'])->value('role') : null;

            if ($role === 'user') {
                $jenisUser = session('jenis_user');

                if ($jenisUser === 'ketua_tim') {
                    $sections = [
                        [
                            'title' => 'Menu',
                            'items' => [
                                [
                                    'label' => 'Dashboard',
                                    'path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                    'route' => 'dashboard',
                                    'active' => request()->routeIs('dashboard'),
                                ],
                                [
                                    'label' => 'Lembur',
                                    'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                    'route' => 'ketua-tim.pengajuan',
                                    'active' => request()->routeIs('ketua-tim.pengajuan'),
                                ],
                                [
                                    'label' => 'Riwayat',
                                    'path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0a9 9 0 0118 0z',
                                    'route' => 'ketua-tim.historis',
                                    'active' => request()->routeIs('ketua-tim.historis'),
                                ],
                            ],
                        ],
                        [
                            'title' => 'Lainnya',
                            'items' => [
                                [
                                    'label' => 'SPKL',
                                    'path' => 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25',
                                    'route' => 'ketua-tim.spkl',
                                    'active' => request()->routeIs('ketua-tim.spkl'),
                                ],
                                [
                                    'label' => 'Laporan',
                                    'path' => 'M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75',
                                    'route' => 'ketua-tim.laporan',
                                    'active' => request()->routeIs('ketua-tim.laporan'),
                                ],
                                [
                                    'label' => 'Akumulasi',
                                    'path' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 10v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'route' => 'ketua-tim.akumulasi',
                                    'active' => request()->routeIs('ketua-tim.akumulasi'),
                                ],
                                [
                                    'label' => 'Daftar Hadir',
                                    'path' => 'M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75',
                                    'route' => 'ketua-tim.daftar_hadir',
                                    'active' => request()->routeIs('ketua-tim.daftar_hadir'),
                                ],
                            ],
                        ],
                    ];
                } else {
                    // anggota biasa / null
                    $sections = [
                        [
                            'title' => 'Menu',
                            'items' => [
                                [
                                    'label' => 'Dashboard',
                                    'path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                    'route' => 'dashboard',
                                    'active' => request()->routeIs('dashboard'),
                                ],
                                [
                                    'label' => 'Lembur',
                                    'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                                    'route' => 'lembur',
                                    'active' => request()->routeIs('lembur'),
                                ],
                                [
                                    'label' => 'Rekapitulasi',
                                    'path' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2',
                                    'route' => 'rekapitulasi',
                                    'active' => request()->routeIs('rekapitulasi'),
                                ],
                            ],
                        ],
                        [
                            'title' => 'Lainnya',
                            'items' => [],
                        ],
                    ];
                }
            } elseif (in_array($role, ['admin', 'superadmin'])) {

                $lainnyaItems = [
                    [
                        'label' => 'SPKL',
                        'path' => 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25',
                        'route' => 'admin.spkl',
                        'active' => request()->routeIs('admin.spkl'),
                    ],
                    [
                        'label' => 'Laporan',
                        'path' => 'M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75',
                        'route' => 'admin.laporan',
                        'active' => request()->routeIs('admin.laporan'),
                    ],
                    [
                        'label' => 'Akumulasi',
                        'path' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 10v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'route' => 'admin.akumulasi',
                        'active' => request()->routeIs('admin.akumulasi'),
                    ],
                    [
                        'label' => 'Daftar Hadir',
                        'path' => 'M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75',
                        'route' => 'admin.daftar_hadir',
                        'active' => request()->routeIs('admin.daftar_hadir'),
                    ],
                ];

                // Tambah menu khusus superadmin
                if ($role === 'superadmin') {
                    $lainnyaItems[] = [
                        'label' => 'Kelola Admin',
                        'path' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
                        'route' => 'superadmin.kelola_admin',
                        'active' => request()->routeIs('superadmin.kelola_admin'),
                    ];
                }

                $sections = [
                    [
                        'title' => 'Menu',
                        'items' => [
                            [
                                'label' => 'Dashboard',
                                'path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                'route' => 'admin.dashboard',
                                'active' => request()->routeIs('admin.dashboard'),
                            ],
                            [
                                'label' => 'Presensi',
                                'path' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
                                'route' => 'admin.presensi',
                                'active' => request()->routeIs('admin.presensi'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Lainnya',
                        'items' => $lainnyaItems,
                    ],
                ];
            } else {
                $sections = [];
            }
        @endphp

        @foreach ($sections as $index => $section)
            @if(!empty($section['items']))
                @if($index > 0)
                @endif

                <div class="px-3 flex flex-col">
                    <p class="px-3 mb-1 text-[10px] font-semibold text-slate-600 uppercase tracking-widest">
                        {{ $section['title'] }}
                    </p>

                    @foreach ($section['items'] as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="relative flex items-center gap-3 w-full h-12 px-4 rounded-xl text-sm transition-colors
                                {{ $item['active']
                                    ? 'bg-[#faa938]/15 text-[#faa938] font-semibold'
                                    : 'text-slate-500 hover:bg-white/5 hover:text-slate-300 font-medium' }}"
                        >
                            @if ($item['active'])
                                <span class="absolute left-0 top-3 bottom-3 w-0.75 bg-[#faa938] rounded-full"></span>
                            @endif

                            <svg
                                class="w-5 h-5 stroke-current shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="{{ $item['active'] ? '2.25' : '1.75' }}"
                                    d="{{ $item['path'] }}"
                                />
                            </svg>

                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </aside>
</body>
