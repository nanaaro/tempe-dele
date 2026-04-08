@extends('layouts.app')

@section('title', 'Laporan Lembur')

@section('content')

<div class="flex items-center gap-3 max-w-7xl mx-auto px-8 my-5">

    {{-- Filter Tanggal --}}
    <div class="relative shrink-0" id="datePicker">
        <button type="button" id="dateBtn"
            class="inline-flex items-center h-10 gap-2 px-4 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-xl hover:border-[#faa938] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
            </svg>
            <span id="dateLabel" class="leading-none">
                {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('M Y') }}
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-50">
                <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
            </svg>
        </button>
        <input type="hidden" id="dateValue" name="date" value="">
        <div id="datePanel" class="hidden absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg p-3">
            <div class="flex items-center justify-between mb-3">
                <button type="button" id="datePrev" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                    </svg>
                </button>
                <span id="dateNavLabel" class="text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938]">Maret 2026</span>
                <button type="button" id="dateNext" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                        <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                    </svg>
                </button>
            </div>
            <div id="dateGrid"></div>
            <div class="flex items-center justify-between mt-3">
                <button type="button" id="btnToday" class="text-sm font-medium text-gray-500 hover:text-[#faa938]">Hari ini</button>
                <button type="button" id="btnDateClose" class="px-3 py-1 text-sm font-medium rounded-full border border-gray-200 text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">Tutup</button>
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

    {{-- Spacer --}}
    <div class="flex-1"></div>
    <div class="h-6 self-center border-l border-gray-200"></div>

    {{-- Generate Laporan --}}
    <a href="javascript:void(0)" onclick="openModalLaporan()" title="Generate Laporan"
        class="flex items-center gap-2 px-3 py-2 rounded-full border border-gray-200 bg-white text-gray-500 hover:border-gray-600 hover:text-gray-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
        Generate Laporan
    </a>

    {{-- Download Laporan --}}
<div class="relative" id="dropdownDownloadWrapper">
    <button type="button" onclick="toggleDownloadDropdown()" title="Unduh Laporan"
        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
    </button>
    <div id="dropdownDownload" class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg z-50">
        <p class="px-4 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">PNS</p>
        <a href="{{ route('admin.dokumen.download', ['type' => 'laporan_pns', 'bulan' => $bulan]) }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/>
            </svg>
            PDF
        </a>
        <a href="{{ route('admin.dokumen.download.excel', ['jenis' => 'pns', 'bulan' => $bulan]) }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6zm2-5l2-3-2-3h1.5l1.25 2 1.25-2H13l-2 3 2 3h-1.5L10.25 16 9 18H7.5z"/>
            </svg>
            Excel
        </a>
        <div class="border-t border-gray-100 mx-3 my-1"></div>
        <p class="px-4 pt-2 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">PPPK</p>
        <a href="{{ route('admin.dokumen.download', ['type' => 'laporan_pppk', 'bulan' => $bulan]) }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/>
            </svg>
            PDF
        </a>
        <a href="{{ route('admin.dokumen.download.excel', ['jenis' => 'pppk', 'bulan' => $bulan]) }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-b-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6zm2-5l2-3-2-3h1.5l1.25 2 1.25-2H13l-2 3 2 3h-1.5L10.25 16 9 18H7.5z"/>
            </svg>
            Excel
        </a>
    </div>
</div>

</div>

<div id="modalLaporan" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeModalLaporan()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Generate Laporan</h2>
                <button onclick="closeModalLaporan()" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
            </div>
            <form id="formGenerateLaporan" method="GET" class="px-6 py-5 space-y-5">
                <input type="hidden" name="bulan" id="inputBulanLaporan">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pegawai</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis" value="pns" checked
                                class="accent-[#faa938]">
                            <span class="text-sm text-gray-700">PNS</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis" value="pppk"
                                class="accent-[#faa938]">
                            <span class="text-sm text-gray-700">PPPK</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModalLaporan()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white transition">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="overflow-hidden max-w-7xl mx-auto px-8 my-5">
    <table class="min-w-full rounded-xl">
        <thead>
            <tr class="bg-gray-50">
                <th class="p-5 text-center text-sm font-semibold text-gray-900 rounded-tl-xl">Nama Pegawai</th>
                <th class="p-5 text-center text-sm font-semibold text-gray-900">NIP</th>
                <th class="p-5 text-center text-sm font-semibold text-gray-900">Tanggal</th>
                <th class="p-5 text-center text-sm font-semibold text-gray-900 rounded-tr-xl">Uraian Kegiatan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
            @forelse($laporan as $l)
            <tr class="bg-white hover:bg-gray-50 text-center">
                <td class="p-5 text-sm text-gray-900 text-left">{{ $l->nama }}</td>
                <td class="p-5 text-sm text-gray-900">{{ $l->nip }}</td>
                <td class="p-5 text-sm text-gray-900">{{ $l->kode }}</td>
                <td class="p-5 text-sm text-gray-900 text-left">{{ $l->uraian ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-8 text-center text-sm text-gray-400">
                    Tidak ada data laporan untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($laporan->hasPages())
<div class="flex justify-center mt-6">
    <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
        @if($laporan->onFirstPage())
            <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </span>
        @else
            <a href="{{ $laporan->previousPageUrl() }}" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </a>
        @endif

        <p class="text-gray-500">Page {{ $laporan->currentPage() }} of {{ $laporan->lastPage() }}</p>

        @if($laporan->hasMorePages())
            <a href="{{ $laporan->nextPageUrl() }}" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
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
@endif

<script>
// =====================
// GLOBAL STATE
// =====================
const now = new Date();
let selectedEmployeeId = null;
let cachedPegawai = [];

function pad2(n) { return String(n).padStart(2, '0'); }

function makeBtn(text, cls, onClick) {
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = text;
    b.className = cls;
    b.addEventListener('click', (e) => { e.stopPropagation(); onClick(); });
    return b;
}

// =====================
// DROPDOWN PEGAWAI
// =====================
function populateDropdown(filter = '', data = []) {
    const list = document.getElementById('listPegawai');
    list.innerHTML = '';

    const liSemua = document.createElement('li');
    liSemua.className = 'cursor-pointer px-4 py-2 text-sm text-gray-400 hover:bg-gray-50';
    liSemua.textContent = 'Semua pegawai';
    liSemua.onclick = () => pilihPegawai(null);
    list.appendChild(liSemua);

    data.filter(e => `${e.nama} ${e.nip}`.toLowerCase().includes(filter.toLowerCase()))
        .forEach(emp => {
            const li = document.createElement('li');
            li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
            li.textContent = `${emp.nama} - ${emp.nip}`;
            li.onclick = () => pilihPegawai(emp);
            list.appendChild(li);
        });
}

window.toggleDropdown = function () {
    document.getElementById('dropdownPegawai').classList.toggle('hidden');
    populateDropdown('', cachedPegawai);
};

window.filterDropdown = function () {
    const search = document.getElementById('searchPegawai').value;
    populateDropdown(search, cachedPegawai);
    document.getElementById('dropdownPegawai').classList.remove('hidden');
};

function pilihPegawai(emp) {
    selectedEmployeeId = emp ? emp.nip : null;
    document.getElementById('searchPegawai').value = emp ? `${emp.nama} - ${emp.nip}` : '';
    document.getElementById('dropdownPegawai').classList.add('hidden');
    updateURL();
}

function updateURL() {
    const params = new URLSearchParams();
    params.set('bulan', '{{ $bulan }}');
    if (selectedEmployeeId) params.set('pegawai', selectedEmployeeId);
    window.location.href = '?' + params.toString();
}

// =====================
// PERIOD PICKER
// =====================
(function () {
    const el = (id) => document.getElementById(id);

    const picker   = el('datePicker');
    const btn      = el('dateBtn');
    const panel    = el('datePanel');
    const grid     = el('dateGrid');
    const navLabel = el('dateNavLabel');
    const dateLabel= el('dateLabel');
    const btnPrev  = el('datePrev');
    const btnNext  = el('dateNext');
    const btnToday = el('btnToday');
    const btnClose = el('btnDateClose');

    if (!picker || !btn || !panel) return;

    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    let viewYear = now.getFullYear();
    let selYear  = {{ \Carbon\Carbon::parse($bulan . '-01')->year }};
    let selMonth = {{ \Carbon\Carbon::parse($bulan . '-01')->month - 1 }};
    let view = 'month';

    function setPeriod(y, m) {
        selYear = y; selMonth = m;
        dateLabel.textContent = `${monthShort[m]} ${y}`;
        window.location.href = `?bulan=${y}-${pad2(m + 1)}`;
    }

    function renderMonth() {
        navLabel.textContent = String(viewYear);
        grid.innerHTML = '';
        const g = document.createElement('div');
        g.className = 'grid grid-cols-3 gap-2';
        monthNames.forEach((name, m) => {
            const isSelected = (m === selMonth && viewYear === selYear);
            const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
            const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
            );
            g.appendChild(makeBtn(name.slice(0, 3), cls, () => setPeriod(viewYear, m)));
        });
        grid.appendChild(g);
    }

    function renderYear() {
        view = 'year'; // sudah ada
        const startYear = Math.floor(viewYear / 12) * 12;
        navLabel.textContent = `${startYear} - ${startYear + 11}`;
        navLabel.className = 'text-sm font-medium text-gray-400 select-none cursor-default';
        grid.innerHTML = '';
        const g = document.createElement('div');
        g.className = 'grid grid-cols-3 gap-2';
        for (let y = startYear; y < startYear + 12; y++) {
            const isSelected = (y === selYear);
            const isNow = (y === now.getFullYear());
            const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
            );
            g.appendChild(makeBtn(String(y), cls, () => { viewYear = y; renderMonth(); }));
        }
        grid.appendChild(g);
    }

    function openPanel()  { viewYear = selYear; renderMonth(); panel.classList.remove('hidden'); }
    function closePanel() { panel.classList.add('hidden'); }

    navLabel?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (view === 'month') renderYear();
        else if (view === 'year') renderMonth();
    });

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        panel.classList.contains('hidden') ? openPanel() : closePanel();
    });

    btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); viewYear--; renderMonth(); });
    btnNext?.addEventListener('click', (e) => { e.stopPropagation(); viewYear++; renderMonth(); });
    btnToday?.addEventListener('click', (e) => { e.stopPropagation(); setPeriod(now.getFullYear(), now.getMonth()); });
    btnClose?.addEventListener('click', (e) => { e.stopPropagation(); closePanel(); });

    document.addEventListener('click', (e) => { if (!picker.contains(e.target)) closePanel(); });

    // Init label tanpa redirect
    dateLabel.textContent = `${monthShort[selMonth]} ${selYear}`;
    renderMonth();
})();

// =====================
// DOWNLOAD PICKER
// =====================
(function () {
    const el = (id) => document.getElementById(id);

    const picker        = el('downloadPicker');
    const toggleBtn     = el('downloadBtn');
    const panel         = el('downloadPanel');
    const grid          = el('downloadGrid');
    const navLabel      = el('downloadNavLabel');
    const btnPrev       = el('downloadPrev');
    const btnNext       = el('downloadNext');
    const btnHarian     = el('btnModeHarian');
    const btnBulanan    = el('btnModeBulanan');
    const downloadLabel = el('downloadLabel');
    const btnDownload   = el('downloadBtn2');

    if (!picker || !panel) return;

    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dayNames   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

    let mode = 'harian', view = 'day';
    let viewYear = now.getFullYear(), viewMonth = now.getMonth();
    let selYear = now.getFullYear(), selMonth = now.getMonth(), selDay = now.getDate();
    let selectedValue = '';

    function setDay(y, m, d) {
        selYear = y; selMonth = m; selDay = d;
        selectedValue = `${y}-${pad2(m + 1)}-${pad2(d)}`;
        downloadLabel.textContent = `${d} ${monthShort[m]} ${y}`;
    }

    function setMonth(y, m) {
        selYear = y; selMonth = m;
        selectedValue = `${y}-${pad2(m + 1)}`;
        downloadLabel.textContent = `${monthNames[m]} ${y}`;
    }

    function renderDay() {
        view = 'day';
        navLabel.textContent = `${monthNames[viewMonth]} ${viewYear}`;
        grid.innerHTML = '';
        const base = 'text-sm rounded-lg py-1 transition border ';
        const header = document.createElement('div');
        header.className = 'grid grid-cols-7 mb-1';
        dayNames.forEach(d => {
            const s = document.createElement('span');
            s.className = 'text-center text-xs text-gray-400 py-1';
            s.textContent = d;
            header.appendChild(s);
        });
        grid.appendChild(header);

        const dayGrid = document.createElement('div');
        dayGrid.className = 'grid grid-cols-7 gap-y-1';
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const firstDay    = new Date(viewYear, viewMonth, 1).getDay();
        const daysInPrev  = new Date(viewYear, viewMonth, 0).getDate();

        for (let i = firstDay - 1; i >= 0; i--) {
            const d = daysInPrev - i;
            dayGrid.appendChild(makeBtn(d, base + 'border-transparent text-gray-300', () => {
                let m = viewMonth - 1, y = viewYear;
                if (m < 0) { m = 11; y--; }
                setDay(y, m, d); viewYear = y; viewMonth = m; renderDay();
            }));
        }
        for (let d = 1; d <= daysInMonth; d++) {
            const isSelected = (d === selDay && viewMonth === selMonth && viewYear === selYear);
            const isToday    = (d === now.getDate() && viewMonth === now.getMonth() && viewYear === now.getFullYear());
            const cls = isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                : isToday ? 'border-[#faa938] text-[#faa938] bg-white'
                : 'border-transparent text-gray-700 hover:border-[#faa938] hover:text-[#faa938]';
            const _d = d;
            dayGrid.appendChild(makeBtn(_d, base + cls, () => { setDay(viewYear, viewMonth, _d); renderDay(); }));
        }
        const total = firstDay + daysInMonth;
        const remaining = total % 7 === 0 ? 0 : 7 - (total % 7);
        for (let d = 1; d <= remaining; d++) {
            const _d = d;
            dayGrid.appendChild(makeBtn(_d, base + 'border-transparent text-gray-300', () => {
                let m = viewMonth + 1, y = viewYear;
                if (m > 11) { m = 0; y++; }
                setDay(y, m, _d); viewYear = y; viewMonth = m; renderDay();
            }));
        }
        grid.appendChild(dayGrid);
    }

    function renderMonth() {
        view = 'month';
        navLabel.textContent = String(viewYear);
        grid.innerHTML = '';
        const g = document.createElement('div');
        g.className = 'grid grid-cols-3 gap-2';
        monthNames.forEach((name, m) => {
            const isSelected = (m === selMonth && viewYear === selYear);
            const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
            const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
            );
            g.appendChild(makeBtn(name.slice(0, 3), cls, () => {
                if (mode === 'bulanan') { setMonth(viewYear, m); renderMonth(); }
                else { viewMonth = m; renderDay(); }
            }));
        });
        grid.appendChild(g);
    }

    function navigate(dir) {
            if (view === 'month') {
                viewYear += dir;
                renderMonth();
            } else {
                viewYear += dir * 12;
                renderYear();
            }
        }

    function switchMode(m) {
        mode = m;
        selectedValue = '';
        downloadLabel.textContent = '—';
        viewYear = now.getFullYear(); viewMonth = now.getMonth();
        const activeClass   = 'rounded-full border border-[#faa938] bg-[#fff7ed] px-3 py-2 text-sm font-medium text-[#faa938]';
        const inactiveClass = 'rounded-full border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-600 hover:border-[#faa938] hover:text-[#faa938]';
        btnHarian.className  = mode === 'harian'  ? activeClass : inactiveClass;
        btnBulanan.className = mode === 'bulanan' ? activeClass : inactiveClass;
        mode === 'harian' ? renderDay() : renderMonth();
    }

    function openPanel()  { viewYear = selYear; viewMonth = selMonth; mode === 'harian' ? renderDay() : renderMonth(); panel.classList.remove('hidden'); }
    function closePanel() { panel.classList.add('hidden'); }

    toggleBtn.addEventListener('click', (e) => { e.stopPropagation(); panel.classList.contains('hidden') ? openPanel() : closePanel(); });
    navLabel.addEventListener('click', (e) => { e.stopPropagation(); if (view === 'day') renderMonth(); });
    navLabel.addEventListener('click', (e) => {e.stopPropagation();if (view === 'month') {renderYear();}});
    btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); navigate(-1); });
    btnNext?.addEventListener('click', (e) => { e.stopPropagation(); navigate(1); });
    btnHarian?.addEventListener('click',  (e) => { e.stopPropagation(); switchMode('harian'); });
    btnBulanan?.addEventListener('click', (e) => { e.stopPropagation(); switchMode('bulanan'); });
    btnDownload?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (!selectedValue) { alert('Pilih periode dulu ya!'); return; }
        const url = mode === 'harian'
            ? `/laporan/download/harian?tanggal=${selectedValue}`
            : `/laporan/download/bulanan?periode=${selectedValue}`;
        window.location.href = url;
        closePanel();
    });
    document.addEventListener('click', (e) => { if (!picker.contains(e.target)) closePanel(); });
    switchMode('harian');
})();

// =====================
// INIT
// =====================
document.addEventListener('DOMContentLoaded', function () {
    const params   = new URLSearchParams(window.location.search);
    const nipParam = params.get('pegawai') ?? '';

    fetch('/admin/presensi/pegawai')
        .then(r => r.json())
        .then(data => {
            cachedPegawai = data;
            populateDropdown('', cachedPegawai);
            if (nipParam) {
                selectedNip = nipParam;
                const found = data.find(e => e.nip == nipParam);
                if (found) {
                    selectedEmployeeId = found.nip;  
                    document.getElementById('searchPegawai').value = `${found.nama} - ${found.nip}`;
                }
            }
        });

    document.addEventListener('click', function (e) {
        const wrapperPegawai = document.getElementById('searchPegawai')?.closest('.relative');
        if (wrapperPegawai && !wrapperPegawai.contains(e.target))
            document.getElementById('dropdownPegawai').classList.add('hidden');
    });
});

//Modal Laporan
function openModalLaporan() {
    document.getElementById('inputBulanLaporan').value = "{{ $bulan ?? now()->format('Y-m') }}";
    // set action dinamis berdasarkan radio yang dipilih
    document.querySelectorAll('input[name="jenis"]').forEach(radio => {
        radio.addEventListener('change', updateLaporanAction);
    });
    updateLaporanAction();
    document.getElementById('modalLaporan').classList.remove('hidden');
}

function updateLaporanAction() {
    const jenis = document.querySelector('input[name="jenis"]:checked')?.value ?? 'pns';
    document.getElementById('formGenerateLaporan').action =
        `/admin/dokumen/generate/laporan/${jenis}`;
}

function closeModalLaporan() {
    document.getElementById('modalLaporan').classList.add('hidden');
}

function toggleDownloadDropdown() {
    document.getElementById('dropdownDownload').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('dropdownDownloadWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('dropdownDownload').classList.add('hidden');
    }
});
</script>

@endsection
