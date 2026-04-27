@extends('layouts.app')
@section('title', 'Generate Dokumen')
@section('content')

{{-- Toolbar --}}
<div class="flex items-center gap-3 max-w-7xl mx-auto px-8 my-5">

    {{-- Filter Periode --}}
    <div class="relative shrink-0" id="periodPicker">
        <button type="button" id="periodBtn"
            class="inline-flex items-center h-10 gap-2 px-4 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-xl hover:border-[#faa938] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
            </svg>
            <span id="periodLabel" class="leading-none">
                {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('M Y') }}
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-50">
                <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
            </svg>
        </button>
        <input type="hidden" id="periodValue" name="period" value="">

        <div id="periodPanel" class="hidden absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg p-3">
            <div class="flex items-center justify-between mb-3">
                <button type="button" id="yearPrev"
                    class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                    </svg>
                </button>
                <span id="yearLabel" class="text-sm font-medium text-gray-900">2026</span>
                <button type="button" id="yearNext"
                    class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                        <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-3 gap-2" id="monthGrid"></div>
            <div class="flex items-center justify-between mt-3">
                <button type="button" id="btnThisMonth"
                    class="text-sm font-medium text-gray-500 hover:text-[#faa938]">
                    Bulan ini
                </button>
                <button type="button" id="btnClosePanel"
                    class="px-3 py-1 text-sm font-medium rounded-full border border-gray-200 text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Spacer --}}
    <div class="flex-1"></div>

    {{-- Tombol Generate --}}
    <a href="javascript:void(0)" onclick="openModalGenerate()" title="Generate Dokumen"
        class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:border-gray-300 hover:text-gray-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
    </a>
</div>

{{-- Tabel --}}
<div class="overflow-x-auto max-w-7xl mx-auto px-8 my-5">
    <table class="min-w-full rounded-xl table-auto">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-3 py-2 text-xs font-semibold text-gray-900 rounded-tl-xl">Periode</th>
                <th class="px-3 py-2 text-xs font-semibold text-gray-900">SPKL</th>
                <th class="px-3 py-2 text-xs font-semibold text-gray-900">Laporan PNS</th>
                <th class="px-3 py-2 text-xs font-semibold text-gray-900 rounded-tr-xl">Laporan PPPK</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
            @foreach($periodeList as $periode)
                @php
                    $spkl        = $dokumenList->where('type', 'spkl')->where('periode', $periode)->first();
                    $laporanPns  = $dokumenList->where('type', 'laporan_pns')->where('periode', $periode)->first();
                    $laporanPppk = $dokumenList->where('type', 'laporan_pppk')->where('periode', $periode)->first();
                @endphp
                <tr class="bg-white hover:bg-gray-50">

                    <td class="px-3 py-3 text-xs text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y') }}
                    </td>

                    {{-- SPKL --}}
                    <td class="px-3 py-3 text-center">
                        @if($spkl)
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.dokumen.view', $spkl->id_dokumen) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-black">
                                    <i class="fa-regular fa-file-pdf text-black"></i>
                                    SPKL_{{ $spkl->periode }}.pdf
                                </a>
                                <form method="POST" action="{{ route('admin.dokumen.hapus-satu', $spkl->id_dokumen) }}"
                                    onsubmit="return confirm('Hapus SPKL periode ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-500 ml-1 text-base leading-none">×</button>
                                </form>
                            </div>
                        @else
                            <button type="button" onclick="openModalNomor('{{ $periode }}')" title="Generate SPKL">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </button>
                        @endif
                    </td>

                    {{-- Laporan PNS --}}
                    <td class="px-3 py-3 text-center">
                        @if($laporanPns)
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.dokumen.view', $laporanPns->id_dokumen) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-black">
                                    <i class="fa-regular fa-file-pdf text-black"></i>
                                    Laporan_PNS_{{ $laporanPns->periode }}.pdf
                                </a>
                                <form method="POST" action="{{ route('admin.dokumen.hapus-satu', $laporanPns->id_dokumen) }}"
                                    onsubmit="return confirm('Hapus Laporan PNS periode ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-500 ml-1">×</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('admin.dokumen.generate.laporan', ['jenis' => 'pns', 'bulan' => $periode]) }}"
                                title="Generate Laporan PNS">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </a>
                        @endif
                    </td>

                    {{-- Laporan PPPK --}}
                    <td class="px-3 py-3 text-center">
                        @if($laporanPppk)
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.dokumen.view', $laporanPppk->id_dokumen) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-black">
                                    <i class="fa-regular fa-file-pdf text-black"></i>
                                    Laporan_PPPK_{{ $laporanPppk->periode }}.pdf
                                </a>
                                <form method="POST" action="{{ route('admin.dokumen.hapus-satu', $laporanPppk->id_dokumen) }}"
                                    onsubmit="return confirm('Hapus Laporan PPPK periode ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-500 ml-1">×</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('admin.dokumen.generate.laporan', ['jenis' => 'pppk', 'bulan' => $periode]) }}"
                                title="Generate Laporan PPPK">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </a>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($periodeList->hasPages())
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">

            {{-- Previous --}}
            @if($periodeList->onFirstPage())
                <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </span>
            @else
                <a href="{{ $periodeList->previousPageUrl() }}"
                    class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </a>
            @endif

            <p class="text-gray-500 text-sm">Page {{ $periodeList->currentPage() }} of {{ $periodeList->lastPage() }}</p>

            {{-- Next --}}
            @if($periodeList->hasMorePages())
                <a href="{{ $periodeList->nextPageUrl() }}"
                    class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
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

{{-- MODAL NOMOR SURAT (SPKL) --}}
<div id="modalNomor" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModalNomor()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Generate SPKL</h2>
                <button type="button" onclick="closeModalNomor()"
                    class="text-gray-500 hover:text-gray-700 text-xl leading-none">
                    &times;
                </button>
            </div>
            <form id="formGenerateSpkl" method="GET" action="{{ route('admin.dokumen.generate.spkl') }}"
                class="px-6 py-5 space-y-4">
                <input type="hidden" name="bulan" id="inputBulanSpkl">
                <div class="text-sm text-gray-500 bg-gray-50 rounded-lg px-4 py-2">
                    Periode: <span id="labelPeriodeSpkl" class="font-medium text-gray-800">—</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat</label>
                    <input type="text" name="nomor_surat" required
                        placeholder="558.1/00001/RT.512/2026"
                        class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                    <p class="text-xs text-gray-400 mt-1">Contoh: 558.1/00001/RT.512/2026</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModalNomor()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL LAPORAN --}}
<div id="modalLaporan" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeModalLaporan()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Generate Laporan</h2>
                <button type="button" onclick="closeModalLaporan()"
                    class="text-gray-400 hover:text-gray-700 text-xl leading-none">
                    &times;
                </button>
            </div>
            <form id="formGenerateLaporan" method="GET" action="{{ route('admin.dokumen.generate.laporan', ['jenis' => 'pns']) }}"
                class="px-6 py-5 space-y-5">
                <input type="hidden" name="bulan" id="inputBulanLaporan">
                <div class="text-sm text-gray-500 bg-gray-50 rounded-lg px-4 py-2">
                    Periode: <span id="labelPeriodeLaporan" class="font-medium text-gray-800">—</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pegawai</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis" value="pns" checked class="accent-[#faa938]">
                            <span class="text-sm text-gray-700">PNS</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis" value="pppk" class="accent-[#faa938]">
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

{{-- MODAL GENERATE --}}
<div id="modalGenerate" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModalGenerate()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Generate Dokumen</h2>
                <button type="button" onclick="closeModalGenerate()"
                    class="text-gray-500 hover:text-gray-700 text-xl leading-none">
                    &times;
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                    <div class="flex gap-2">
                        <select id="selectBulan"
                            class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                            <option value="">Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <input type="number" id="inputTahun" placeholder="2024" min="2000" max="2099"
                            class="w-28 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Dokumen</label>
                    <select id="selectDok"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                        <option value="">Pilih jenis dokumen...</option>
                        <option value="spkl">SPKL — Surat Pernyataan Kerja Lembur</option>
                        <option value="laporan">Laporan — Rekap pegawai</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModalGenerate()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" onclick="nextStep()"
                        class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white">
                        Lanjut
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    // =====================
    // HELPER
    // =====================
    const now = new Date();

    function pad2(n) { return String(n).padStart(2, '0'); }

    function makeBtn(text, cls, onClick) {
        const b = document.createElement('button');
        b.type        = 'button';
        b.textContent = text;
        b.className   = cls;
        b.addEventListener('click', (e) => { e.stopPropagation(); onClick(); });
        return b;
    }

    // =====================
    // PERIOD PICKER
    // =====================
    (function () {
        const el = (id) => document.getElementById(id);

        const picker       = el('periodPicker');
        const btn          = el('periodBtn');
        const panel        = el('periodPanel');
        const grid         = el('monthGrid');
        const navLabel     = el('yearLabel');
        const periodLabel  = el('periodLabel');
        const periodValue  = el('periodValue');
        const btnPrev      = el('yearPrev');
        const btnNext      = el('yearNext');
        const btnThisMonth = el('btnThisMonth');
        const btnClose     = el('btnClosePanel');

        if (!picker || !btn || !panel || !grid || !navLabel || !periodLabel || !periodValue) return;

        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        let view     = 'month';
        let selYear  = {{ \Carbon\Carbon::parse($bulan . '-01')->year }};
        let selMonth = {{ \Carbon\Carbon::parse($bulan . '-01')->month - 1 }};
        let viewYear = selYear;

        function updateDisplayOnly(y, m) {
            periodLabel.textContent = `${monthShort[m]} ${y}`;
            periodValue.value       = `${y}-${pad2(m + 1)}`;
        }

        function setPeriod(y, m) {
            selYear  = y;
            selMonth = m;
            updateDisplayOnly(y, m);
            window.location.href = `?bulan=${y}-${pad2(m + 1)}`;
        }

        function openPanel() {
            viewYear = selYear;
            renderMonth();
            panel.classList.remove('hidden');
        }

        function closePanel() {
            panel.classList.add('hidden');
        }

        function renderMonth() {
            view = 'month';
            navLabel.textContent = String(viewYear);
            navLabel.className   = 'text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none';
            grid.innerHTML = '';

            monthNames.forEach((name, m) => {
                const isSelected = (m === selMonth && viewYear === selYear);
                const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected
                        ? 'bg-[#faa938] text-white border-[#faa938]'
                        : isNow
                            ? 'border-[#faa938] text-[#faa938] bg-white'
                            : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );
                grid.appendChild(makeBtn(name.slice(0, 3), cls, () => {
                    setPeriod(viewYear, m);
                    closePanel();
                }));
            });
        }

        function renderYear() {
            view = 'year';
            const startYear = Math.floor(viewYear / 12) * 12;
            navLabel.textContent = `${startYear} - ${startYear + 11}`;
            navLabel.className   = 'text-sm font-medium text-gray-400 select-none cursor-default';
            grid.innerHTML = '';

            for (let y = startYear; y < startYear + 12; y++) {
                const isSelected = (y === selYear);
                const isNow      = (y === now.getFullYear());
                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected
                        ? 'bg-[#faa938] text-white border-[#faa938]'
                        : isNow
                            ? 'border-[#faa938] text-[#faa938] bg-white'
                            : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );
                const _y = y;
                grid.appendChild(makeBtn(String(_y), cls, () => {
                    viewYear = _y;
                    renderMonth();
                }));
            }
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

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.contains('hidden') ? openPanel() : closePanel();
        });

        navLabel.addEventListener('click', (e) => {
            e.stopPropagation();
            if (view === 'month') renderYear();
        });

        btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); navigate(-1); });
        btnNext?.addEventListener('click', (e) => { e.stopPropagation(); navigate(1); });

        btnThisMonth?.addEventListener('click', (e) => {
            e.stopPropagation();
            setPeriod(now.getFullYear(), now.getMonth());
            closePanel();
        });

        btnClose?.addEventListener('click', (e) => { e.stopPropagation(); closePanel(); });

        document.addEventListener('click', (e) => {
            if (!picker.contains(e.target)) closePanel();
        });

        // Tampilkan label awal tanpa redirect
        updateDisplayOnly(selYear, selMonth);
    })();

    // =====================
    // MODAL GENERATE
    // =====================
    const bulanNama = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    window.openModalGenerate = function () {
        document.getElementById('modalGenerate').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    window.closeModalGenerate = function () {
        document.getElementById('modalGenerate').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    window.nextStep = function () {
        const bulan = document.getElementById('selectBulan').value;
        const tahun = document.getElementById('inputTahun').value;
        const dok   = document.getElementById('selectDok').value;

        if (!bulan || !tahun) { alert('Pilih bulan dan isi tahun dulu!'); return; }
        if (!dok)             { alert('Pilih jenis dokumen!'); return; }

        const periodeLabel = bulanNama[+bulan] + ' ' + tahun;
        const periodeValue = tahun + '-' + String(bulan).padStart(2, '0');

        closeModalGenerate();

        if (dok === 'spkl') {
            document.getElementById('inputBulanSpkl').value         = periodeValue;
            document.getElementById('labelPeriodeSpkl').textContent = periodeLabel;
            document.getElementById('modalNomor').classList.remove('hidden');
        } else {
            document.getElementById('inputBulanLaporan').value         = periodeValue;
            document.getElementById('labelPeriodeLaporan').textContent = periodeLabel;
            document.getElementById('modalLaporan').classList.remove('hidden');
        }
    };

    // =====================
    // MODAL NOMOR SURAT (SPKL)
    // =====================
    window.openModalNomor = function (periode) {
        const parts = periode.split('-');
        const label = bulanNama[+parts[1]] + ' ' + parts[0];
        document.getElementById('inputBulanSpkl').value         = periode;
        document.getElementById('labelPeriodeSpkl').textContent = label;
        document.getElementById('formGenerateSpkl').action      = '{{ route("admin.dokumen.generate.spkl") }}';
        document.getElementById('modalNomor').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    window.closeModalNomor = function () {
        document.getElementById('modalNomor').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    // =====================
    // MODAL LAPORAN
    // =====================
    window.closeModalLaporan = function () {
        document.getElementById('modalLaporan').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.getElementById('formGenerateLaporan').addEventListener('submit', function (e) {
        e.preventDefault();
        const jenis = this.querySelector('input[name="jenis"]:checked').value;
        this.action = '/admin/dokumen/generate/laporan/' + jenis;
        this.submit();
    });

})();
</script>

@endsection
