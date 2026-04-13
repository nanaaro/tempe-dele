@extends('layouts.app')

@section('title', 'Rekapitulasi Lembur')

@section('content')

<div class="flex items-center gap-3 max-w-7xl mx-auto px-8 my-5">

        {{-- Filter Periode --}}
        <div class="relative shrink-0" id="periodPicker">
            <button type="button" id="periodBtn"
                class="inline-flex items-center h-10 gap-2 px-4 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-xl hover:border-[#faa938] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                    <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
                </svg>
                <span id="periodLabel" class="leading-none">{{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('M Y') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-50">
                    <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
                </svg>
            </button>
            <input type="hidden" id="periodValue" name="period" value="">
            <div id="periodPanel" class="hidden absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg p-3">
                <div class="flex items-center justify-between mb-3">
                    <button type="button" id="yearPrev" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                            <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                        </svg>
                    </button>
                    <span id="yearLabel" class="text-sm font-medium text-gray-900">2026</span>
                    <button type="button" id="yearNext" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                            <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2" id="monthGrid"></div>
                <div class="flex items-center justify-between mt-3">
                    <button type="button" id="btnThisMonth" class="text-sm font-medium text-gray-500 hover:text-[#faa938]">Bulan ini</button>
                    <button type="button" id="btnClosePanel" class="px-3 py-1 text-sm font-medium rounded-full border border-gray-200 text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">Tutup</button>
                </div>
            </div>
        </div>

        {{-- Filter Pegawai --}}
        <div class="relative shrink-0">
            <input type="text" id="searchPegawai" placeholder="Cari nama pegawai..."
                onclick="toggleDropdown()" oninput="filterDropdown()" autocomplete="off"
                class="w-120 h-10 rounded-xl border border-gray-200 bg-white pl-4 pr-8 text-sm text-gray-700 focus:border-[#faa938] focus:outline-none focus:ring-2 focus:ring-[#faa938]/20"/>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-3 w-3 text-gray-400">
                    <path fill="currentColor" d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/>
                </svg>
            </div>
            <div id="dropdownPegawai" class="hidden absolute z-10 mt-1 w-full max-h-48 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg">
                <ul id="listPegawai"></ul>
            </div>
        </div>

    {{-- Spacer atau devider --}}
    <div class="flex-1"></div>

        {{-- Input Nomer Surat
        <a href="javascript:void(0)" onclick="openModalNomor()" title="Input Nomer Surat"
            class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:border-[#faa938] hover:text-[#faa938] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>
        </a> --}}

    {{-- tombol download --}}
        <a href="{{ route('admin.rekapitulasi.export', ['bulan' => $bulan]) }}"
            title="Unduh Rekapitulasi"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
        </a>

</div>

<div class="max-w-7xl mx-auto px-8 my-5">
    <div class="overflow-x-auto rounded-xl border border-gray-200">
        <table class="min-w-full rounded-xl">
            <thead>
                <tr class="bg-gray-50">
                    <th class="sticky left-0 z-5 bg-gray-50 p-4 text-left text-xs font-semibold text-gray-900">Nama</th>
                    <th class="sticky left-16 z-5 bg-gray-50 p-4 text-left text-xs font-semibold text-gray-900">NIP</th>
                    @for ($i = 1; $i <= 12; $i++)
                    <th class="p-4 text-center text-xs font-semibold text-gray-900 whitespace-nowrap">HB {{ $i }}</th>
                    @endfor
                    @for ($i = 1; $i <= 16; $i++)
                    <th class="p-4 text-center text-xs font-semibold text-gray-900 whitespace-nowrap">HL {{ $i }}</th>
                    @endfor
                    <th class="p-4 text-center text-xs font-semibold text-gray-900 whitespace-nowrap">Jumlah HB</th>
                    <th class="p-4 text-center text-xs font-semibold text-gray-900 whitespace-nowrap">Jumlah HL</th>
                    <th class="p-4 text-center text-xs font-semibold text-gray-900 whitespace-nowrap">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse($rekapitulasi as $r)
                <tr class="bg-white hover:bg-gray-50 transition-all duration-300">
                    <td class="sticky left-0 z-5 bg-white p-4 text-xs text-gray-900 min-w-[180px]">{{ $r['nama'] }}</td>
                    <td class="sticky left-[180px] z-5 bg-white p-4 text-xs text-gray-900 whitespace-nowrap">{{ $r['nip'] }}</td>
                    @for ($i = 1; $i <= 12; $i++)
                    <td class="p-4 text-xs text-gray-900 text-center">{{ $r['hb'.$i] ?? '-' }}</td>
                    @endfor
                    @for ($i = 1; $i <= 16; $i++)
                    <td class="p-4 text-xs text-gray-900 text-center">{{ $r['hl'.$i] ?? '-' }}</td>
                    @endfor
                    <td class="p-4 text-xs text-gray-900 text-center font-semibold">{{ $r['jumlah_hb'] ?: '-' }}</td>
                    <td class="p-4 text-xs text-gray-900 text-center font-semibold">{{ $r['jumlah_hl'] ?: '-' }}</td>
                    <td class="p-4 text-xs text-gray-900 text-center whitespace-nowrap">{{ $r['tanggal'] ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="31" class="p-8 text-center text-sm text-gray-400">
                        Tidak ada data lembur untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="flex justify-center mt-6">
    <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
        @if($rekapitulasi->onFirstPage())
            <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </span>
        @else
            <a href="{{ $rekapitulasi->previousPageUrl() }}" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </a>
        @endif

        <p class="text-gray-500">Page {{ $rekapitulasi->currentPage() }} of {{ $rekapitulasi->lastPage() }}</p>

        @if($rekapitulasi->hasMorePages())
            <a href="{{ $rekapitulasi->nextPageUrl() }}" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </a>
        @else
            <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </span>
        @endif
    </nav>
</div>

{{-- Modal Nomor Surat --}}
<div id="modalNomor" class="fixed inset-0 z-50 hidden">

    <!-- Overlay (lebih ringan) -->
    <div class="absolute inset-0 bg-black/30" onclick="closeModalNomor()"></div>

    <!-- Modal -->
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">
                    Generate SPKL
                </h2>
                <button onclick="closeModalNomor()"
                    class="text-gray-400 hover:text-gray-700 text-xl leading-none">
                    &times;
                </button>
            </div>

            <!-- Form -->
            <form id="formGenerateSpkl" method="GET" action="{{ route('admin.dokumen.generate.spkl') }}"
                class="px-6 py-5 space-y-5">

                <input type="hidden" name="bulan" id="inputBulanSpkl">

                <!-- Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Surat
                    </label>
                    <input type="text" name="nomor_surat" required
                        placeholder="558.1/00001/RT.512/2026"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-1 focus:ring-gray-800">
                    <p class="text-xs text-gray-400 mt-1">
                        Contoh: 558.1/00001/RT.512/2026
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModalNomor()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938]
                               rounded-lg hover:bg-[#fd9a10] hover:text-white transition">
                        Generate
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // =====================
    // GLOBAL STATE
    // =====================
    const now = new Date();
    let selectedNip   = null;
    let cachedPegawai = [];
    let selYear       = {{ \Carbon\Carbon::parse($bulan . '-01')->year }};
    let selMonth      = {{ \Carbon\Carbon::parse($bulan . '-01')->month - 1 }};

    function pad2(n) { return String(n).padStart(2, '0'); }

    function makeBtn(text, cls, onClick) {
        const b = document.createElement('button');
        b.type      = 'button';
        b.textContent = text;
        b.className = cls;
        b.addEventListener('click', (e) => { e.stopPropagation(); onClick(); });
        return b;
    }

    // =====================
    // UPDATE URL (single source of truth)
    // =====================
    function updateURL() {
        const params = new URLSearchParams();
        params.set('bulan', `${selYear}-${pad2(selMonth + 1)}`);
        if (selectedNip) params.set('nip_lama', selectedNip);
        window.location.href = '?' + params.toString();
    }

    // =====================
    // PERIOD PICKER
    // =====================
    (function () {
        const el = (id) => document.getElementById(id);

        const picker    = el('periodPicker');
        const btn       = el('periodBtn');
        const panel     = el('periodPanel');
        const grid      = el('monthGrid');
        const navLabel  = el('yearLabel');
        const dateLabel = el('periodLabel');
        const btnPrev   = el('yearPrev');
        const btnNext   = el('yearNext');
        const btnThis   = el('btnThisMonth');
        const btnClose  = el('btnClosePanel');

        if (!picker || !btn || !panel) return;

        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        let viewYear = selYear;
        let view     = 'month';

        function renderMonth() {
            view = 'month';
            navLabel.textContent = String(viewYear);
            grid.innerHTML = '';

            monthNames.forEach((name, m) => {
                const isSelected = (m === selMonth && viewYear === selYear);
                const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                    :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );
                grid.appendChild(makeBtn(name.slice(0, 3), cls, () => {  // ✅ langsung ke grid
                    selYear  = viewYear;
                    selMonth = m;
                    dateLabel.textContent = `${monthShort[m]} ${viewYear}`;
                    closePanel();
                    updateURL();
                }));
            });
        }

        function renderYear() {
            view = 'year';
            const startYear = Math.floor(viewYear / 12) * 12;
            navLabel.textContent = `${startYear} - ${startYear + 11}`;
            grid.innerHTML = '';

            const g = document.createElement('div');
            g.className = 'grid grid-cols-3 gap-2';

            for (let y = startYear; y < startYear + 12; y++) {
                const isSelected = (y === selYear);
                const isNow      = (y === now.getFullYear());
                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                    :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );
                const _y = y;
                g.appendChild(makeBtn(String(_y), cls, () => { viewYear = _y; renderMonth(); }));
            }

            grid.appendChild(g);
        }

        function navigate(dir) {
            view === 'month' ? viewYear += dir : viewYear += dir * 12;
            view === 'month' ? renderMonth() : renderYear();
        }

        function openPanel()  { viewYear = selYear; renderMonth(); panel.classList.remove('hidden'); }
        function closePanel() { panel.classList.add('hidden'); }

        // Init label tanpa redirect
        dateLabel.textContent = `${monthShort[selMonth]} ${selYear}`;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.contains('hidden') ? openPanel() : closePanel();
        });

        navLabel.addEventListener('click', (e) => {
            e.stopPropagation();
            view === 'month' ? renderYear() : renderMonth();
        });

        btnPrev?.addEventListener('click',  (e) => { e.stopPropagation(); navigate(-1); });
        btnNext?.addEventListener('click',  (e) => { e.stopPropagation(); navigate(1); });
        btnThis?.addEventListener('click',  (e) => {
            e.stopPropagation();
            selYear  = now.getFullYear();
            selMonth = now.getMonth();
            closePanel();
            updateURL();
        });
        btnClose?.addEventListener('click', (e) => { e.stopPropagation(); closePanel(); });

        document.addEventListener('click', (e) => {
            if (!picker.contains(e.target)) closePanel();
        });
    })();

    // =====================
    // DROPDOWN PEGAWAI
    // =====================
    function populateDropdown(filter = '') {
        const list = document.getElementById('listPegawai');
        list.innerHTML = '';

        // Opsi reset
        const liSemua = document.createElement('li');
        liSemua.className   = 'cursor-pointer px-4 py-2 text-sm text-gray-400 hover:bg-gray-50';
        liSemua.textContent = 'Semua pegawai';
        liSemua.addEventListener('click', () => pilihPegawai(null));
        list.appendChild(liSemua);

        const filtered = cachedPegawai.filter(e =>
            `${e.nama} ${e.nip}`.toLowerCase().includes(filter.toLowerCase())
        );

        if (filtered.length === 0) {
            const li = document.createElement('li');
            li.className   = 'px-4 py-2 text-sm text-gray-400';
            li.textContent = 'Tidak ditemukan';
            list.appendChild(li);
            return;
        }

        filtered.forEach(emp => {
            const li = document.createElement('li');
            li.className   = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
            li.textContent = `${emp.nama} - ${emp.nip}`;
            li.addEventListener('click', () => pilihPegawai(emp));
            list.appendChild(li);
        });
    }

    function pilihPegawai(emp) {
        selectedNip = emp ? emp.nip_lama : null;
        document.getElementById('searchPegawai').value = emp ? `${emp.nama} - ${emp.nip}` : '';
        document.getElementById('dropdownPegawai').classList.add('hidden');
        updateURL();
    }

    window.toggleDropdown = function () {
        document.getElementById('dropdownPegawai').classList.toggle('hidden');
        populateDropdown('');
    };

    window.filterDropdown = function () {
        populateDropdown(document.getElementById('searchPegawai').value);
        document.getElementById('dropdownPegawai').classList.remove('hidden');
    };

    // =====================
    // INIT
    // =====================
    document.addEventListener('DOMContentLoaded', function () {
        const params   = new URLSearchParams(window.location.search);
        const nipParam = params.get('nip_lama') ?? '';

        fetch('/admin/presensi/pegawai')
            .then(r => r.json())
            .then(data => {
                cachedPegawai = data;
                populateDropdown('');

                if (nipParam) {
                    selectedNip  = nipParam;
                    const found  = data.find(e => e.nip_lama == nipParam);
                    if (found) {
                        document.getElementById('searchPegawai').value = `${found.nama} - ${found.nip}`;
                    }
                }
            });

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('searchPegawai')?.closest('.relative');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('dropdownPegawai').classList.add('hidden');
            }
        });
    });
</script>

@endsection
