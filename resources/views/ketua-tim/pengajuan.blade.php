@extends('layouts.app')

@section('title', 'Daftar Pengajuan Lembur - ' . ($tim->nama_tim ?? ''))

@section('content')

<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10">

    <div class="flex items-center gap-3 max-w-7xl my-5">

        {{-- Datepicker --}}
        <div class="relative shrink-0" id="datePicker">
            <button type="button" id="dateBtn"
                class="inline-flex items-center h-10 gap-2 px-4 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-full hover:border-[#faa938] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                    <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
                </svg>
                <span id="dateLabel" class="leading-none">Semua Tanggal</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-50">
                    <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
                </svg>
            </button>
            <input type="hidden" id="dateValue" value="">

            <div id="datePanel" class="hidden absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg p-3">
                <div class="flex items-center justify-between mb-3">
                    <button type="button" id="datePrev" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                            <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                        </svg>
                    </button>
                    <span id="dateNavLabel" class="text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none"></span>
                    <button type="button" id="dateNext" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                            <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                        </svg>
                    </button>
                </div>
                <div id="dateGrid"></div>
                <div class="flex items-center justify-between mt-3">
                    <button type="button" id="btnResetDate" class="text-sm font-medium text-gray-500 hover:text-[#faa938]">Hari ini</button>
                    <button type="button" id="btnDateClose" class="px-3 py-1 text-sm font-medium rounded-full border border-gray-200 text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">Tutup</button>
                </div>
            </div>
        </div>

        {{-- Filter Pegawai --}}
        <div class="relative shrink-0">
            <input type="text" id="searchPegawai" placeholder="Cari nama pegawai..."
                onclick="toggleDropdownPegawai()" oninput="filterDropdownPegawai()" autocomplete="off"
                class="w-120 h-10 rounded-full border border-gray-200 bg-white pl-4 pr-8 text-sm text-gray-700 focus:border-[#faa938] focus:outline-none focus:ring-2 focus:ring-[#faa938]/20"/>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-3 w-3 text-gray-400">
                    <path fill="currentColor" d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/>
                </svg>
            </div>
            <div id="dropdownPegawai" class="hidden absolute z-10 mt-1 w-full max-h-48 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg">
                <ul id="listPegawai"></ul>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full rounded-xl table-auto">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 rounded-tl-xl w-8">No</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900">Nama Pegawai</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900">Tanggal Lembur</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900">Jam Diajukan</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900">Jam Disetujui</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900">Uraian Kegiatan</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900">Data Presensi</th>
                    <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 rounded-tr-xl">Keputusan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300" id="tabelPengajuan">
                @forelse($pengajuan as $i => $p)
                <tr class="bg-white transition-all duration-500 hover:bg-gray-50"
                    data-nama="{{ strtolower($p->nama_pegawai) }}"
                    data-tanggal="{{ $p->date }}">
                    <td class="px-2 py-2 text-xs text-gray-900 text-center">
                        {{ $pengajuan->firstItem() + $i }}
                    </td>
                    <td class="px-2 py-2 text-xs text-gray-900">
                        <div>{{ $p->nama_pegawai }}</div>
                        <div class="text-xs text-gray-400">{{ $p->nip_pegawai }}</div>
                    </td>
                    <td class="px-2 py-2 text-xs text-gray-900 text-left">
                        {{ \Carbon\Carbon::parse($p->date)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-2 py-2 text-xs text-gray-900 text-center">
                        {{ $p->jam_mulai ? substr($p->jam_mulai,0,5).' - '.substr($p->jam_selesai,0,5) : '-' }}
                    </td>
                    <td class="px-2 py-2 text-xs text-gray-900 text-center" id="jam-disetujui-{{ $p->id_transaksi }}">
                        @if($p->jam_mulai_disetujui && $p->jam_selesai_disetujui)
                            {{ substr($p->jam_mulai_disetujui,0,5) }} - {{ substr($p->jam_selesai_disetujui,0,5) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-2 py-2 text-xs text-gray-900">
                        {{ $p->uraian ?? '-' }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        <button onclick="openModalPresensi({{ $p->id_transaksi }})"
                            class="inline-flex items-center gap-1 text-xs font-medium cursor-pointer
                            {{ $p->has_presensi ? 'text-green-600 underline' : 'text-gray-400' }}">
                            {{ $p->has_presensi ? 'Informasi tersedia' : 'Tidak ada informasi' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </button>
                    </td>
                    <td class="px-2 py-2 text-center" id="status-{{ $p->id_transaksi }}">
                        <div class="inline-flex items-center gap-2">
                            @if($p->status === 'pending')
                                <span class="bg-amber-100 rounded-full px-2 text-xs text-amber-700 py-0.5">Menunggu</span>
                                <button onclick="openModalKeputusan({{ $p->id_transaksi }}, '{{ substr($p->jam_mulai,0,5) }}', '{{ substr($p->jam_selesai,0,5) }}')"
                                    class="text-amber-400 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @elseif($p->status === 'approved')
                                <span class="bg-green-100 rounded-full px-2 text-xs text-green-700 py-0.5">Disetujui</span>
                                <button onclick="openModalKeputusan({{ $p->id_transaksi }}, '{{ substr($p->jam_mulai_disetujui,0,5) }}', '{{ substr($p->jam_selesai_disetujui,0,5) }}')"
                                    class="text-gray-400 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @elseif($p->status === 'rejected')
                                <span class="bg-red-100 rounded-full px-2 text-xs text-red-600 py-0.5">Ditolak</span>
                                <button onclick="openModalKeputusan({{ $p->id_transaksi }}, '{{ substr($p->jam_mulai_disetujui,0,5) }}', '{{ substr($p->jam_selesai_disetujui,0,5) }}')"
                                    class="text-gray-400 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 py-8 text-center text-sm text-gray-400">
                        Belum ada pengajuan lembur.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pengajuan->hasPages())
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
            @if($pengajuan->onFirstPage())
                <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </span>
            @else
                <a class="p-1 rounded border hover:bg-[#faa938] hover:text-white hover:border-[#faa938]" href="{{ $pengajuan->previousPageUrl() }}">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </a>
            @endif

            <p class="text-gray-500 text-sm">Page {{ $pengajuan->currentPage() }} of {{ $pengajuan->lastPage() }}</p>

            @if($pengajuan->hasMorePages())
                <a class="p-1 rounded border hover:bg-[#faa938] hover:text-white hover:border-[#faa938]" href="{{ $pengajuan->nextPageUrl() }}">
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
</div>

{{-- Modal Presensi --}}
<div id="modalPresensi" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModalPresensi()"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Informasi Presensi</h2>
                    <p class="text-xs text-gray-400 mt-0.5" id="presensiSubtitle">-</p>
                </div>
                <button onclick="closeModalPresensi()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>

            <div class="px-6 py-5 space-y-5" id="presensiBody">
                {{-- loading state --}}
                <p class="text-sm text-gray-400 text-center py-4">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Keputusan --}}
<div id="modalKeputusan" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModalKeputusan()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Keputusan Lembur</h2>
                <button onclick="closeModalKeputusan()" class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
            </div>

            {{-- Warning durasi --}}
            <div id="warningDurasi" class="hidden mx-6 mt-4 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
                ⚠️ <span id="warningText"></span>
            </div>

            <div class="px-6 py-5 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai Disetujui</label>
                        <input id="kJamMulai" type="time"
                            class="border rounded-lg px-3 py-2 text-sm w-full outline-none border-gray-300 focus:ring-1 focus:ring-gray-400" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai Disetujui</label>
                        <input id="kJamSelesai" type="time"
                            class="border rounded-lg px-3 py-2 text-sm w-full outline-none border-gray-300 focus:ring-1 focus:ring-gray-400" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea id="kCatatan" rows="3"
                        class="border rounded-lg px-3 py-2 text-sm w-full outline-none border-gray-300 focus:ring-1 focus:ring-gray-400"
                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="setKeputusan('rejected')" id="kBtnTolak"
                            class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all">
                            Tolak
                        </button>
                        <button onclick="setKeputusan('approved')" id="kBtnSetujui"
                            class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all">
                            Setujui
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 border-t">
                <button onclick="closeModalKeputusan()" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                <button onclick="simpanKeputusan()" id="btnSimpan"
                    class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white transition-all">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// =====================
// HELPER
// =====================
function makeBtn(text, cls, onClick) {
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = text;
    b.className = cls;
    b.addEventListener('click', (e) => { e.stopPropagation(); onClick(); });
    return b;
}

// =====================
// STATE FILTER
// =====================
let selectedDate = null;
let selectedNip  = null;
let cachedAnggota = [];

// =====================
// FETCH ANGGOTA TIM
// =====================
fetch('/ketua-tim/pengajuan/anggota')
    .then(r => r.json())
    .then(data => {
        cachedAnggota = data;
        renderDropdownPegawai('');
    });

function renderDropdownPegawai(filter) {
    const list = document.getElementById('listPegawai');
    list.innerHTML = '';

    const liSemua = document.createElement('li');
    liSemua.className = 'cursor-pointer px-4 py-2 text-sm text-gray-400 hover:bg-gray-50';
    liSemua.textContent = 'Semua anggota';
    liSemua.onclick = () => pilihPegawai(null);
    list.appendChild(liSemua);

    cachedAnggota
        .filter(e => `${e.nama} ${e.nip}`.toLowerCase().includes(filter.toLowerCase()))
        .forEach(emp => {
            const li = document.createElement('li');
            li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
            li.textContent = `${emp.nama} — ${emp.nip}`;
            li.onclick = () => pilihPegawai(emp);
            list.appendChild(li);
        });
}

window.toggleDropdownPegawai = function () {
    document.getElementById('dropdownPegawai').classList.toggle('hidden');
    renderDropdownPegawai('');
};

window.filterDropdownPegawai = function () {
    const search = document.getElementById('searchPegawai').value;
    renderDropdownPegawai(search);
    document.getElementById('dropdownPegawai').classList.remove('hidden');
};

function pilihPegawai(emp) {
    selectedNip = emp ? emp.nip : null;
    document.getElementById('searchPegawai').value = emp ? `${emp.nama} — ${emp.nip}` : '';
    document.getElementById('dropdownPegawai').classList.add('hidden');
    filterTabel();
    updateResetBtn();
}

// =====================
// DATE PICKER
// =====================
(function () {
    const el = (id) => document.getElementById(id);

    const picker    = el('datePicker');
    const btn       = el('dateBtn');
    const panel     = el('datePanel');
    const grid      = el('dateGrid');
    const navLabel  = el('dateNavLabel');
    const dateLabel = el('dateLabel');
    const dateValue = el('dateValue');
    const btnPrev   = el('datePrev');
    const btnNext   = el('dateNext');
    const btnToday  = el('btnToday');
    const btnClose  = el('btnDateClose');

    if (!picker || !btn || !panel) return;

    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dayNames   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

    const now = new Date();
    let view      = 'day';
    let viewYear  = now.getFullYear();
    let viewMonth = now.getMonth();
    let selYear   = null;
    let selMonth  = null;
    let selDay    = null;

    function pad2(n) { return String(n).padStart(2, '0'); }

    function setDate(y, m, d) {
        selYear = y; selMonth = m; selDay = d;
        selectedDate = `${y}-${pad2(m + 1)}-${pad2(d)}`;
        dateLabel.textContent = `${d} ${monthShort[m]} ${y}`;
        dateValue.value = selectedDate;
        filterTabel();
        updateResetBtn();
    }

    function clearDate() {
        selYear = null; selMonth = null; selDay = null;
        selectedDate = null;
        dateLabel.textContent = 'Semua Tanggal';
        dateValue.value = '';
        filterTabel();
        updateResetBtn();
    }

    function openPanel() {
        viewYear  = now.getFullYear();
        viewMonth = now.getMonth();
        renderDay();
        panel.classList.remove('hidden');
    }

    function closePanel() {
        panel.classList.add('hidden');
    }

    // ---- RENDER DAY ----
    function renderDay() {
        view = 'day';
        navLabel.textContent = `${monthNames[viewMonth]} ${viewYear}`;
        navLabel.className = 'text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none';
        grid.innerHTML = '';

        const header = document.createElement('div');
        header.className = 'grid grid-cols-7 mb-1';
        dayNames.forEach(d => {
            const span = document.createElement('span');
            span.className = 'text-center text-xs text-gray-400 py-1';
            span.textContent = d;
            header.appendChild(span);
        });
        grid.appendChild(header);

        const dayGrid = document.createElement('div');
        dayGrid.className = 'grid grid-cols-7 gap-y-1';

        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const firstDay    = new Date(viewYear, viewMonth, 1).getDay();
        const daysInPrev  = new Date(viewYear, viewMonth, 0).getDate();
        const base = 'text-sm rounded-lg py-1 transition border ';

        // Tanggal bulan sebelumnya
        for (let i = firstDay - 1; i >= 0; i--) {
            const d = daysInPrev - i;
            dayGrid.appendChild(makeBtn(d, base + 'border-transparent text-gray-300 hover:border-gray-200', () => {
                let m = viewMonth - 1, y = viewYear;
                if (m < 0) { m = 11; y--; }
                setDate(y, m, d);
                viewYear = y; viewMonth = m;
                renderDay();
            }));
        }

        // Tanggal bulan ini
        for (let d = 1; d <= daysInMonth; d++) {
            const isSelected = (selDay === d && selMonth === viewMonth && selYear === viewYear);
            const isToday    = (d === now.getDate() && viewMonth === now.getMonth() && viewYear === now.getFullYear());
            const cls = isSelected
            ? 'bg-[#faa938] text-white border-[#faa938]'
            : isToday
                ? 'bg-[#faa938]/20 text-[#faa938] border-transparent'
                : 'border-transparent text-gray-700 hover:border-[#faa938] hover:text-[#faa938]';
            const _d = d;
            dayGrid.appendChild(makeBtn(_d, base + cls, () => {
                setDate(viewYear, viewMonth, _d);
                closePanel();
            }));
        }

        // Tanggal bulan berikutnya
        const total     = firstDay + daysInMonth;
        const remaining = total % 7 === 0 ? 0 : 7 - (total % 7);
        for (let d = 1; d <= remaining; d++) {
            const _d = d;
            dayGrid.appendChild(makeBtn(_d, base + 'border-transparent text-gray-300 hover:border-gray-200', () => {
                let m = viewMonth + 1, y = viewYear;
                if (m > 11) { m = 0; y++; }
                setDate(y, m, _d);
                viewYear = y; viewMonth = m;
                renderDay();
            }));
        }

        grid.appendChild(dayGrid);
    }

    // ---- RENDER MONTH ----
    function renderMonth() {
        view = 'month';
        navLabel.textContent = String(viewYear);
        navLabel.className = 'text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none';
        grid.innerHTML = '';

        const g = document.createElement('div');
        g.className = 'grid grid-cols-3 gap-2';

        monthNames.forEach((name, m) => {
            const isSelected = (m === selMonth && viewYear === selYear);
            const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
            const cls = isSelected
                ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow
                    ? 'border-[#faa938] text-[#faa938] bg-white'
                    : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]';
            g.appendChild(makeBtn(name.slice(0, 3), 'px-2 py-2 text-sm rounded-lg border transition ' + cls, () => {
                viewMonth = m;
                renderDay();
            }));
        });

        grid.appendChild(g);
    }

    // ---- RENDER YEAR ----
    function renderYear() {
        view = 'year';
        const startYear = Math.floor(viewYear / 12) * 12;
        navLabel.textContent = `${startYear} - ${startYear + 11}`;
        navLabel.className = 'text-sm font-medium text-gray-400 select-none cursor-default';
        grid.innerHTML = '';

        const g = document.createElement('div');
        g.className = 'grid grid-cols-3 gap-2';

        for (let y = startYear; y < startYear + 12; y++) {
            const isSelected = (y === selYear);
            const isNow      = (y === now.getFullYear());
            const cls = isSelected
                ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow
                    ? 'border-[#faa938] text-[#faa938] bg-white'
                    : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]';
            const _y = y;
            g.appendChild(makeBtn(_y, 'px-2 py-2 text-sm rounded-lg border transition ' + cls, () => {
                viewYear = _y;
                renderMonth();
            }));
        }

        grid.appendChild(g);
    }

    // ---- EVENT LISTENERS ----
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        panel.classList.contains('hidden') ? openPanel() : closePanel();
    });

    navLabel.addEventListener('click', (e) => {
        e.stopPropagation();
        if (view === 'day')   renderMonth();
        else if (view === 'month') renderYear();
    });

    btnPrev?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (view === 'day') {
            viewMonth--;
            if (viewMonth < 0) { viewMonth = 11; viewYear--; }
            renderDay();
        } else if (view === 'month') {
            viewYear--;
            renderMonth();
        } else if (view === 'year') {
            viewYear -= 12;
            renderYear();
        }
    });

    btnNext?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (view === 'day') {
            viewMonth++;
            if (viewMonth > 11) { viewMonth = 0; viewYear++; }
            renderDay();
        } else if (view === 'month') {
            viewYear++;
            renderMonth();
        } else if (view === 'year') {
            viewYear += 12;
            renderYear();
        }
    });

    btnToday?.addEventListener('click', (e) => {
        e.stopPropagation();
        viewYear  = now.getFullYear();
        viewMonth = now.getMonth();
        setDate(now.getFullYear(), now.getMonth(), now.getDate());
        closePanel();
    });

    btnClose?.addEventListener('click', (e) => {
        e.stopPropagation();
        closePanel();
    });

    // Klik di luar panel → tutup
    document.addEventListener('click', (e) => {
        if (!picker.contains(e.target)) closePanel();
        if (!document.getElementById('dropdownPegawai')?.contains(e.target) &&
            !document.getElementById('searchPegawai')?.contains(e.target)) {
            document.getElementById('dropdownPegawai')?.classList.add('hidden');
        }
    });

})();

// =====================
// FILTER TABEL
// =====================
function filterTabel() {
    document.querySelectorAll('#tabelPengajuan tr').forEach(row => {
        const cocokTanggal = !selectedDate || row.dataset.tanggal === selectedDate;
        const cocokNip     = !selectedNip  || row.dataset.nip === selectedNip;
        row.style.display  = (cocokTanggal && cocokNip) ? '' : 'none';
    });
}

function updateResetBtn() {
    const btn = document.getElementById('btnResetFilter');
    if (!btn) return;
    (selectedDate || selectedNip) ? btn.classList.remove('hidden') : btn.classList.add('hidden');
}

document.getElementById('btnResetFilter')?.addEventListener('click', () => {
    selectedDate = null;
    selectedNip  = null;
    document.getElementById('dateLabel').textContent = 'Semua Tanggal';
    document.getElementById('dateValue').value = '';
    document.getElementById('searchPegawai').value = '';
    filterTabel();
    updateResetBtn();
});

// =====================
// MODAL PRESENSI
// =====================
window.openModalPresensi = function(id) {
    document.getElementById('presensiSubtitle').textContent = '-';
    document.getElementById('presensiBody').innerHTML = '<p class="text-sm text-gray-400 text-center py-4">Memuat data...</p>';
    document.getElementById('modalPresensi').classList.remove('hidden');

    fetch(`/ketua-tim/pengajuan/${id}/presensi`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('presensiSubtitle').textContent = `${data.nama} · ${data.nip}`;
        document.getElementById('presensiBody').innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <div class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">${data.tanggal}</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Kehadiran</label>
                <div class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">
                    ${data.status ?? '<span class="text-gray-400">Tidak ada data presensi</span>'}
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Kedatangan</label>
                    <div class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">${data.jam_masuk ?? '-'}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Kepulangan</label>
                    <div class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">${data.jam_pulang ?? '-'}</div>
                </div>
            </div>
        `;
    })
    .catch(() => {
        document.getElementById('presensiBody').innerHTML = '<p class="text-sm text-red-400 text-center py-4">Gagal memuat data presensi.</p>';
    });
};

window.closeModalPresensi = function() {
    document.getElementById('modalPresensi').classList.add('hidden');
};

// =====================
// MODAL KEPUTUSAN
// =====================
let currentId  = null;
let keputusan  = null;

window.openModalKeputusan = function(id, jamMulai, jamSelesai) {
    currentId = id;
    keputusan = null;
    document.getElementById('kJamMulai').value   = jamMulai || '';
    document.getElementById('kJamSelesai').value = jamSelesai || '';
    document.getElementById('kCatatan').value    = '';
    resetBtnKeputusan();
    cekWarningDurasi();
    document.getElementById('modalKeputusan').classList.remove('hidden');
};

window.closeModalKeputusan = function() {
    document.getElementById('modalKeputusan').classList.add('hidden');
    currentId = null;
    keputusan = null;
};

window.setKeputusan = function(val) {
    keputusan = val;
    resetBtnKeputusan();
    if (val === 'rejected') {
        document.getElementById('kBtnTolak').className = 'px-4 py-2 text-sm font-semibold rounded-lg border border-red-400 bg-red-50 text-red-600 transition-all';
    } else {
        document.getElementById('kBtnSetujui').className = 'px-4 py-2 text-sm font-semibold rounded-lg border border-green-400 bg-green-50 text-green-700 transition-all';
    }
};

function resetBtnKeputusan() {
    document.getElementById('kBtnTolak').className   = 'px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all';
    document.getElementById('kBtnSetujui').className = 'px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all';
}

function cekWarningDurasi() {
    const mulai   = document.getElementById('kJamMulai').value;
    const selesai = document.getElementById('kJamSelesai').value;
    const warning = document.getElementById('warningDurasi');
    const text    = document.getElementById('warningText');
    if (!mulai || !selesai) { warning.classList.add('hidden'); return; }
    const [jm, mm] = mulai.split(':').map(Number);
    const [js, ms] = selesai.split(':').map(Number);
    const jam = Math.floor(((js * 60 + ms) - (jm * 60 + mm)) / 60);
    if (jam > 6) {
        text.textContent = `Durasi ${jam} jam melebihi batas maksimal lembur hari libur (6 jam).`;
        warning.classList.remove('hidden');
    } else if (jam > 4) {
        text.textContent = `Durasi ${jam} jam melebihi batas maksimal lembur hari kerja (4 jam).`;
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
}

document.getElementById('kJamMulai')?.addEventListener('change', cekWarningDurasi);
document.getElementById('kJamSelesai')?.addEventListener('change', cekWarningDurasi);

window.simpanKeputusan = function() {
    if (!keputusan) { alert('Pilih keputusan terlebih dahulu.'); return; }

    const jamMulai   = document.getElementById('kJamMulai').value;
    const jamSelesai = document.getElementById('kJamSelesai').value;
    const catatan    = document.getElementById('kCatatan').value;

    if (!jamMulai || !jamSelesai) { alert('Jam mulai dan jam selesai wajib diisi.'); return; }

    const btn = document.getElementById('btnSimpan');
    btn.disabled    = true;
    btn.textContent = 'Menyimpan...';

    fetch(`/ketua-tim/pengajuan/${currentId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            status:                keputusan,
            jam_mulai_disetujui:   jamMulai,
            jam_selesai_disetujui: jamSelesai,
            note:                  catatan,
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const statusEl = document.getElementById(`status-${currentId}`);
            if (keputusan === 'approved') {
                statusEl.innerHTML = `
                    <div class="inline-flex items-center gap-2">
                        <span class="bg-green-100 rounded-full px-3 text-xs text-green-700 py-0.5">Disetujui</span>
                        <button onclick="openModalKeputusan(${currentId}, '${jamMulai}', '${jamSelesai}')" class="text-gray-400 cursor-pointer" title="Ubah keputusan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>`;
            } else {
                statusEl.innerHTML = `
                    <div class="inline-flex items-center gap-2">
                        <span class="bg-red-100 rounded-full px-3 text-xs text-red-600 py-0.5">Ditolak</span>
                        <button onclick="openModalKeputusan(${currentId}, '${jamMulai}', '${jamSelesai}')" class="text-gray-400 cursor-pointer" title="Ubah keputusan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>`;
            }

            const jamEl = document.getElementById(`jam-disetujui-${currentId}`);
            if (jamEl) {
                if (keputusan === 'approved') {
                    jamEl.textContent = `${jamMulai} - ${jamSelesai}`;
                } else {
                    jamEl.textContent = '-';
                }
            }
            closeModalKeputusan();
        }
    })
    .catch(() => alert('Gagal menyimpan, coba lagi.'))
    .finally(() => {
        btn.disabled    = false;
        btn.textContent = 'Simpan';
    });
};

</script>
@endpush

@endsection
