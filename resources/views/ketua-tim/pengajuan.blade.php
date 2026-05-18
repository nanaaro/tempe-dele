@extends('layouts.app')

@section('title', 'Daftar Pengajuan Lembur - ' . ($tim->nama_tim ?? ''))

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-5">

    {{-- Toolbar --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">

        {{-- Datepicker --}}
        <div class="relative w-full sm:w-auto" id="datePicker">
            <button type="button" id="dateBtn"
                class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-full border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:border-[#faa938] sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-4 w-4 shrink-0 fill-current">
                    <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
                </svg>

                <span id="dateLabel" class="truncate leading-none">Semua Tanggal</span>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="h-3 w-3 shrink-0 fill-current opacity-50">
                    <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
                </svg>
            </button>

            <input type="hidden" id="dateValue" value="">

            <div id="datePanel"
                class="absolute left-0 z-50 mt-2 hidden w-[calc(100vw-2rem)] max-w-72 rounded-xl border border-gray-200 bg-white p-3 shadow-lg sm:w-72">
                <div class="mb-3 flex items-center justify-between">
                    <button type="button" id="datePrev"
                        class="rounded-lg border border-gray-200 p-2 transition-all hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="h-3 w-3 fill-current">
                            <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                        </svg>
                    </button>

                    <span id="dateNavLabel"
                        class="cursor-pointer select-none text-sm font-medium text-gray-900 hover:text-[#faa938]">
                    </span>

                    <button type="button" id="dateNext"
                        class="rounded-lg border border-gray-200 p-2 transition-all hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="h-3 w-3 fill-current">
                            <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                        </svg>
                    </button>
                </div>

                <div id="dateGrid"></div>

                <div class="mt-3 flex items-center justify-between">
                    <button type="button" id="btnResetDate"
                        class="text-sm font-medium text-gray-500 hover:text-[#faa938]">
                        Hari ini
                    </button>

                    <button type="button" id="btnDateClose"
                        class="rounded-full border border-gray-200 px-3 py-1 text-sm font-medium text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- Filter Pegawai --}}
        <div class="relative w-full sm:w-[22rem]">
            <input type="text" id="searchPegawai" placeholder="Cari nama pegawai..."
                onclick="toggleDropdownPegawai()" oninput="filterDropdownPegawai()" autocomplete="off"
                class="h-10 w-full rounded-full border border-gray-200 bg-white pl-4 pr-8 text-sm text-gray-700 focus:border-[#faa938] focus:outline-none focus:ring-2 focus:ring-[#faa938]/20">

            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-3 w-3 text-gray-400">
                    <path fill="currentColor" d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/>
                </svg>
            </div>

            <div id="dropdownPegawai"
                class="absolute z-40 mt-1 hidden max-h-48 w-full overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg">
                <ul id="listPegawai"></ul>
            </div>
        </div>

        {{-- Reset filter --}}
        <button type="button" id="btnResetFilter"
            class="hidden h-10 rounded-full border border-gray-200 bg-white px-4 text-sm font-medium text-gray-500 transition-colors hover:border-red-300 hover:text-red-400">
            Reset
        </button>
    </div>

    {{-- Tabel: mobile/tablet/desktop tetap tabel, hanya horizontal scroll --}}
    <div class="overflow-x-auto rounded-xl bg-white">
        <table class="w-full min-w-[1080px] table-auto rounded-xl">
            <thead>
                <tr class="bg-gray-100">
                    <th class="w-12 rounded-tl-xl px-3 py-3 text-center text-xs font-semibold text-gray-900">No</th>
                    <th class="w-48 px-3 py-3 text-center text-xs font-semibold text-gray-900">Nama Pegawai</th>
                    <th class="w-32 px-3 py-3 text-center text-xs font-semibold text-gray-900">Tanggal Lembur</th>
                    <th class="w-28 px-3 py-3 text-center text-xs font-semibold text-gray-900">Jam Diajukan</th>
                    <th class="w-28 px-3 py-3 text-center text-xs font-semibold text-gray-900">Jam Disetujui</th>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-900">Uraian Kegiatan</th>
                    <th class="w-40 px-3 py-3 text-center text-xs font-semibold text-gray-900">Data Presensi</th>
                    <th class="w-32 rounded-tr-xl px-3 py-3 text-center text-xs font-semibold text-gray-900">Keputusan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-300" id="tabelPengajuan">
                @forelse($pengajuan as $i => $p)
                    @php
                        $jamMulaiDefault = $p->jam_mulai_disetujui
                            ? substr($p->jam_mulai_disetujui, 0, 5)
                            : ($p->jam_mulai ? substr($p->jam_mulai, 0, 5) : '');

                        $jamSelesaiDefault = $p->jam_selesai_disetujui
                            ? substr($p->jam_selesai_disetujui, 0, 5)
                            : ($p->jam_selesai ? substr($p->jam_selesai, 0, 5) : '');
                    @endphp

                    <tr class="bg-white transition-all duration-200 hover:bg-gray-50"
                        data-nama="{{ strtolower($p->nama_pegawai) }}"
                        data-nip="{{ $p->nip_pegawai }}"
                        data-tanggal="{{ $p->date }}">

                        <td class="px-3 py-3 text-center text-xs text-gray-900">
                            {{ $pengajuan->firstItem() + $i }}
                        </td>

                        <td class="px-3 py-3 text-xs text-gray-900">
                            <div class="max-w-[180px] whitespace-normal break-words">
                                {{ $p->nama_pegawai }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $p->nip_pegawai }}
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-left text-xs text-gray-900">
                            {{ \Carbon\Carbon::parse($p->date)->translatedFormat('d F Y') }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-center text-xs text-gray-900">
                            {{ $p->jam_mulai ? substr($p->jam_mulai, 0, 5) . ' - ' . substr($p->jam_selesai, 0, 5) : '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-center text-xs text-gray-900" id="jam-disetujui-{{ $p->id_transaksi }}">
                            @if($p->jam_mulai_disetujui && $p->jam_selesai_disetujui)
                                {{ substr($p->jam_mulai_disetujui, 0, 5) }} - {{ substr($p->jam_selesai_disetujui, 0, 5) }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-3 py-3 text-xs text-gray-900">
                            <div class="max-w-[280px] whitespace-normal break-words">
                                {{ $p->uraian ?? '-' }}
                            </div>
                        </td>

                        <td class="px-3 py-3 text-center">
                            <button type="button" onclick="openModalPresensi({{ $p->id_transaksi }})"
                                class="inline-flex items-center justify-center gap-1 text-xs font-medium
                                {{ $p->has_presensi ? 'text-green-600 underline' : 'text-gray-400' }}">
                                {{ $p->has_presensi ? 'Informasi tersedia' : 'Tidak ada informasi' }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"/>
                                </svg>
                            </button>
                        </td>

                        <td class="px-3 py-3 text-center" id="status-{{ $p->id_transaksi }}">
                            <div class="inline-flex items-center justify-center gap-2">
                                @if($p->status === 'pending')
                                    <span class="whitespace-nowrap rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Menunggu</span>
                                @elseif($p->status === 'approved')
                                    <span class="whitespace-nowrap rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">Disetujui</span>
                                @elseif($p->status === 'rejected')
                                    <span class="whitespace-nowrap rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-600">Ditolak</span>
                                @endif

                                <button type="button"
                                    onclick="openModalKeputusan({{ $p->id_transaksi }}, '{{ $jamMulaiDefault }}', '{{ $jamSelesaiDefault }}')"
                                    class="{{ $p->status === 'pending' ? 'text-amber-400 hover:text-amber-600' : 'text-gray-400 hover:text-gray-600' }} cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-8 text-center text-sm text-gray-400">
                            Belum ada pengajuan lembur.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pengajuan->hasPages())
        <div class="mt-6 flex justify-center">
            <nav class="inline-flex items-center gap-2 rounded bg-white p-1">

                @if($pengajuan->onFirstPage())
                    <span class="cursor-not-allowed rounded border p-1 text-gray-300">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </span>
                @else
                    <a class="rounded border bg-white p-1 text-black hover:border-[#faa938] hover:bg-[#faa938] hover:text-white"
                        href="{{ $pengajuan->previousPageUrl() }}">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </a>
                @endif

                <p class="whitespace-nowrap text-sm text-gray-500">
                    Page {{ $pengajuan->currentPage() }} of {{ $pengajuan->lastPage() }}
                </p>

                @if($pengajuan->hasMorePages())
                    <a class="rounded border bg-white p-1 text-black hover:border-[#faa938] hover:bg-[#faa938] hover:text-white"
                        href="{{ $pengajuan->nextPageUrl() }}">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </a>
                @else
                    <span class="cursor-not-allowed rounded border p-1 text-gray-300">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
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

    <div class="relative flex min-h-full items-center justify-center px-4 py-6">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-5 py-4 sm:px-6">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Informasi Presensi</h2>
                    <p class="mt-0.5 text-xs text-gray-400" id="presensiSubtitle">-</p>
                </div>

                <button type="button" onclick="closeModalPresensi()"
                    class="text-xl leading-none text-gray-400 hover:text-gray-600">
                    &times;
                </button>
            </div>

            <div class="space-y-4 px-5 py-5 sm:px-6" id="presensiBody">
                <p class="py-4 text-center text-sm text-gray-400">Memuat data...</p>
            </div>

        </div>
    </div>
</div>

{{-- Modal Keputusan --}}
<div id="modalKeputusan" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModalKeputusan()"></div>

    <div class="relative flex min-h-screen items-center justify-center px-4 py-6">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-5 py-4 sm:px-6">
                <h2 class="text-base font-semibold text-gray-900 sm:text-lg">Keputusan Lembur</h2>

                <button type="button" onclick="closeModalKeputusan()"
                    class="text-xl leading-none text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>

            {{-- Warning durasi --}}
            <div id="warningDurasi" class="mx-5 mt-4 hidden rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-xs text-amber-700 sm:mx-6">
                ⚠️ <span id="warningText"></span>
            </div>

            <div class="space-y-5 px-5 py-5 sm:px-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jam Mulai Disetujui</label>
                        <input id="kJamMulai" type="time"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-[#faa938] focus:ring-2 focus:ring-[#faa938]/20">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jam Selesai Disetujui</label>
                        <input id="kJamSelesai" type="time"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-[#faa938] focus:ring-2 focus:ring-[#faa938]/20">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea id="kCatatan" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-[#faa938] focus:ring-2 focus:ring-[#faa938]/20"
                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Keputusan</label>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <button type="button" onclick="setKeputusan('rejected')" id="kBtnTolak"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition-all hover:border-red-300 hover:bg-red-50 hover:text-red-600">
                            Tolak
                        </button>

                        <button type="button" onclick="setKeputusan('approved')" id="kBtnSetujui"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition-all hover:border-green-300 hover:bg-green-50 hover:text-green-700">
                            Setujui
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button type="button" onclick="closeModalKeputusan()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium hover:bg-gray-50 sm:w-auto sm:py-2">
                    Batal
                </button>

                <button type="button" onclick="simpanKeputusan()" id="btnSimpan"
                    class="w-full rounded-lg bg-[#faa938] px-4 py-2.5 text-center text-sm font-semibold text-black transition-all hover:bg-[#fd9a10] hover:text-white sm:w-auto sm:py-2">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function makeBtn(text, className, onClick) {
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = text;
    button.className = className;
    button.addEventListener('click', function (event) {
        event.stopPropagation();
        onClick();
    });
    return button;
}

function pad2(value) {
    return String(value).padStart(2, '0');
}

let selectedDate = null;
let selectedNip = null;
let cachedAnggota = [];

// =====================
// FETCH ANGGOTA TIM
// =====================
fetch('/ketua-tim/pengajuan/anggota')
    .then(response => response.json())
    .then(data => {
        cachedAnggota = Array.isArray(data) ? data : [];
        renderDropdownPegawai('');
    })
    .catch(() => {
        cachedAnggota = [];
    });

function renderDropdownPegawai(filter = '') {
    const list = document.getElementById('listPegawai');
    if (!list) return;

    list.innerHTML = '';

    const liSemua = document.createElement('li');
    liSemua.className = 'cursor-pointer px-4 py-2 text-sm text-gray-400 hover:bg-gray-50';
    liSemua.textContent = 'Semua anggota';
    liSemua.onclick = () => pilihPegawai(null);
    list.appendChild(liSemua);

    const keyword = filter.toLowerCase();

    cachedAnggota
        .filter(emp => `${emp.nama ?? ''} ${emp.nip ?? ''}`.toLowerCase().includes(keyword))
        .forEach(emp => {
            const li = document.createElement('li');
            li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
            li.textContent = `${emp.nama} — ${emp.nip}`;
            li.onclick = () => pilihPegawai(emp);
            list.appendChild(li);
        });
}

window.toggleDropdownPegawai = function () {
    const dropdown = document.getElementById('dropdownPegawai');
    const search = document.getElementById('searchPegawai');

    if (!dropdown || !search) return;

    dropdown.classList.toggle('hidden');
    renderDropdownPegawai(search.value);
};

window.filterDropdownPegawai = function () {
    const dropdown = document.getElementById('dropdownPegawai');
    const search = document.getElementById('searchPegawai');

    if (!dropdown || !search) return;

    renderDropdownPegawai(search.value);
    dropdown.classList.remove('hidden');
};

function pilihPegawai(emp) {
    selectedNip = emp ? emp.nip : null;

    const search = document.getElementById('searchPegawai');
    const dropdown = document.getElementById('dropdownPegawai');

    if (search) search.value = emp ? `${emp.nama} — ${emp.nip}` : '';
    if (dropdown) dropdown.classList.add('hidden');

    filterTabel();
    updateResetBtn();
}

// =====================
// FILTER TABEL
// =====================
function filterTabel() {
    document.querySelectorAll('#tabelPengajuan tr[data-tanggal]').forEach(row => {
        const cocokTanggal = !selectedDate || row.dataset.tanggal === selectedDate;
        const cocokNip = !selectedNip || row.dataset.nip === selectedNip;

        row.style.display = cocokTanggal && cocokNip ? '' : 'none';
    });
}

function updateResetBtn() {
    const btn = document.getElementById('btnResetFilter');
    if (!btn) return;

    if (selectedDate || selectedNip) {
        btn.classList.remove('hidden');
    } else {
        btn.classList.add('hidden');
    }
}

document.getElementById('btnResetFilter')?.addEventListener('click', () => {
    selectedDate = null;
    selectedNip = null;

    document.getElementById('dateLabel').textContent = 'Semua Tanggal';
    document.getElementById('dateValue').value = '';
    document.getElementById('searchPegawai').value = '';

    filterTabel();
    updateResetBtn();
});

// =====================
// DATE PICKER
// =====================
(function () {
    const picker = document.getElementById('datePicker');
    const btn = document.getElementById('dateBtn');
    const panel = document.getElementById('datePanel');
    const grid = document.getElementById('dateGrid');
    const navLabel = document.getElementById('dateNavLabel');
    const dateLabel = document.getElementById('dateLabel');
    const dateValue = document.getElementById('dateValue');
    const btnPrev = document.getElementById('datePrev');
    const btnNext = document.getElementById('dateNext');
    const btnToday = document.getElementById('btnResetDate');
    const btnClose = document.getElementById('btnDateClose');

    if (!picker || !btn || !panel || !grid || !navLabel) return;

    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dayNames = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

    const now = new Date();

    let view = 'day';
    let viewYear = now.getFullYear();
    let viewMonth = now.getMonth();
    let selYear = null;
    let selMonth = null;
    let selDay = null;

    function setDate(year, month, day) {
        selYear = year;
        selMonth = month;
        selDay = day;

        selectedDate = `${year}-${pad2(month + 1)}-${pad2(day)}`;

        dateLabel.textContent = `${day} ${monthShort[month]} ${year}`;
        dateValue.value = selectedDate;

        filterTabel();
        updateResetBtn();
    }

    function openPanel() {
        if (selYear !== null && selMonth !== null) {
            viewYear = selYear;
            viewMonth = selMonth;
        } else {
            viewYear = now.getFullYear();
            viewMonth = now.getMonth();
        }

        renderDay();
        panel.classList.remove('hidden');
    }

    function closePanel() {
        panel.classList.add('hidden');
    }

    function renderDay() {
        view = 'day';

        navLabel.textContent = `${monthNames[viewMonth]} ${viewYear}`;
        navLabel.className = 'cursor-pointer select-none text-sm font-medium text-gray-900 hover:text-[#faa938]';

        grid.innerHTML = '';

        const header = document.createElement('div');
        header.className = 'mb-1 grid grid-cols-7';

        dayNames.forEach(day => {
            const span = document.createElement('span');
            span.className = 'py-1 text-center text-xs text-gray-400';
            span.textContent = day;
            header.appendChild(span);
        });

        grid.appendChild(header);

        const dayGrid = document.createElement('div');
        dayGrid.className = 'grid grid-cols-7 gap-y-1';

        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const firstDay = new Date(viewYear, viewMonth, 1).getDay();
        const daysInPrev = new Date(viewYear, viewMonth, 0).getDate();

        const base = 'rounded-lg border py-1 text-sm transition ';

        for (let i = firstDay - 1; i >= 0; i--) {
            const day = daysInPrev - i;

            dayGrid.appendChild(makeBtn(day, base + 'border-transparent text-gray-300 hover:border-gray-200', () => {
                let month = viewMonth - 1;
                let year = viewYear;

                if (month < 0) {
                    month = 11;
                    year--;
                }

                setDate(year, month, day);
                closePanel();
            }));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const isSelected = day === selDay && selMonth === viewMonth && selYear === viewYear;
            const isToday = day === now.getDate() && viewMonth === now.getMonth() && viewYear === now.getFullYear();

            const className = isSelected
                ? 'border-[#faa938] bg-[#faa938] text-white'
                : isToday
                    ? 'border-transparent bg-[#faa938]/20 text-[#faa938]'
                    : 'border-transparent text-gray-700 hover:border-[#faa938] hover:text-[#faa938]';

            dayGrid.appendChild(makeBtn(day, base + className, () => {
                setDate(viewYear, viewMonth, day);
                closePanel();
            }));
        }

        const total = firstDay + daysInMonth;
        const remaining = total % 7 === 0 ? 0 : 7 - (total % 7);

        for (let day = 1; day <= remaining; day++) {
            dayGrid.appendChild(makeBtn(day, base + 'border-transparent text-gray-300 hover:border-gray-200', () => {
                let month = viewMonth + 1;
                let year = viewYear;

                if (month > 11) {
                    month = 0;
                    year++;
                }

                setDate(year, month, day);
                closePanel();
            }));
        }

        grid.appendChild(dayGrid);
    }

    function renderMonth() {
        view = 'month';

        navLabel.textContent = String(viewYear);
        navLabel.className = 'cursor-pointer select-none text-sm font-medium text-gray-900 hover:text-[#faa938]';

        grid.innerHTML = '';

        const monthGrid = document.createElement('div');
        monthGrid.className = 'grid grid-cols-3 gap-2';

        monthNames.forEach((name, month) => {
            const isSelected = month === selMonth && viewYear === selYear;
            const isNow = month === now.getMonth() && viewYear === now.getFullYear();

            const className = isSelected
                ? 'border-[#faa938] bg-[#faa938] text-white'
                : isNow
                    ? 'border-[#faa938] bg-white text-[#faa938]'
                    : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]';

            monthGrid.appendChild(makeBtn(name.slice(0, 3), 'rounded-lg border px-2 py-2 text-sm transition ' + className, () => {
                viewMonth = month;
                renderDay();
            }));
        });

        grid.appendChild(monthGrid);
    }

    function renderYear() {
        view = 'year';

        const startYear = Math.floor(viewYear / 12) * 12;

        navLabel.textContent = `${startYear} - ${startYear + 11}`;
        navLabel.className = 'cursor-default select-none text-sm font-medium text-gray-400';

        grid.innerHTML = '';

        const yearGrid = document.createElement('div');
        yearGrid.className = 'grid grid-cols-3 gap-2';

        for (let year = startYear; year < startYear + 12; year++) {
            const isSelected = year === selYear;
            const isNow = year === now.getFullYear();

            const className = isSelected
                ? 'border-[#faa938] bg-[#faa938] text-white'
                : isNow
                    ? 'border-[#faa938] bg-white text-[#faa938]'
                    : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]';

            yearGrid.appendChild(makeBtn(year, 'rounded-lg border px-2 py-2 text-sm transition ' + className, () => {
                viewYear = year;
                renderMonth();
            }));
        }

        grid.appendChild(yearGrid);
    }

    btn.addEventListener('click', event => {
        event.stopPropagation();

        if (panel.classList.contains('hidden')) {
            openPanel();
        } else {
            closePanel();
        }
    });

    navLabel.addEventListener('click', event => {
        event.stopPropagation();

        if (view === 'day') {
            renderMonth();
        } else if (view === 'month') {
            renderYear();
        }
    });

    btnPrev?.addEventListener('click', event => {
        event.stopPropagation();

        if (view === 'day') {
            viewMonth--;

            if (viewMonth < 0) {
                viewMonth = 11;
                viewYear--;
            }

            renderDay();
        } else if (view === 'month') {
            viewYear--;
            renderMonth();
        } else if (view === 'year') {
            viewYear -= 12;
            renderYear();
        }
    });

    btnNext?.addEventListener('click', event => {
        event.stopPropagation();

        if (view === 'day') {
            viewMonth++;

            if (viewMonth > 11) {
                viewMonth = 0;
                viewYear++;
            }

            renderDay();
        } else if (view === 'month') {
            viewYear++;
            renderMonth();
        } else if (view === 'year') {
            viewYear += 12;
            renderYear();
        }
    });

    btnToday?.addEventListener('click', event => {
        event.stopPropagation();

        viewYear = now.getFullYear();
        viewMonth = now.getMonth();

        setDate(now.getFullYear(), now.getMonth(), now.getDate());
        closePanel();
    });

    btnClose?.addEventListener('click', event => {
        event.stopPropagation();
        closePanel();
    });

    document.addEventListener('click', event => {
        if (!picker.contains(event.target)) {
            closePanel();
        }

        const dropdownPegawai = document.getElementById('dropdownPegawai');
        const searchPegawai = document.getElementById('searchPegawai');

        if (dropdownPegawai && searchPegawai && !dropdownPegawai.contains(event.target) && !searchPegawai.contains(event.target)) {
            dropdownPegawai.classList.add('hidden');
        }
    });
})();

// =====================
// MODAL PRESENSI
// =====================
window.openModalPresensi = function(id) {
    const modal = document.getElementById('modalPresensi');
    const subtitle = document.getElementById('presensiSubtitle');
    const body = document.getElementById('presensiBody');

    subtitle.textContent = '-';
    body.innerHTML = '<p class="py-4 text-center text-sm text-gray-400">Memuat data...</p>';

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    fetch(`/ketua-tim/pengajuan/${id}/presensi`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        subtitle.textContent = `${data.nama} · ${data.nip}`;

        body.innerHTML = `
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal</label>
                <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700">${data.tanggal}</div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Status Kehadiran</label>
                <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700">
                    ${data.status ?? '<span class="text-gray-400">Tidak ada data presensi</span>'}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Jam Kedatangan</label>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700">${data.jam_masuk ?? '-'}</div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Jam Kepulangan</label>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700">${data.jam_pulang ?? '-'}</div>
                </div>
            </div>
        `;
    })
    .catch(() => {
        body.innerHTML = '<p class="py-4 text-center text-sm text-red-400">Gagal memuat data presensi.</p>';
    });
};

window.closeModalPresensi = function() {
    document.getElementById('modalPresensi').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

// =====================
// MODAL KEPUTUSAN
// =====================
let currentId = null;
let keputusan = null;

window.openModalKeputusan = function(id, jamMulai, jamSelesai) {
    currentId = id;
    keputusan = null;

    document.getElementById('kJamMulai').value = jamMulai || '';
    document.getElementById('kJamSelesai').value = jamSelesai || '';
    document.getElementById('kCatatan').value = '';

    resetBtnKeputusan();
    cekWarningDurasi();

    document.getElementById('modalKeputusan').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
};

window.closeModalKeputusan = function() {
    document.getElementById('modalKeputusan').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');

    currentId = null;
    keputusan = null;
};

window.setKeputusan = function(value) {
    keputusan = value;
    resetBtnKeputusan();

    if (value === 'rejected') {
        document.getElementById('kBtnTolak').className = 'rounded-lg border border-red-400 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition-all';
    } else {
        document.getElementById('kBtnSetujui').className = 'rounded-lg border border-green-400 bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700 transition-all';
    }
};

function resetBtnKeputusan() {
    document.getElementById('kBtnTolak').className = 'rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition-all hover:border-red-300 hover:bg-red-50 hover:text-red-600';
    document.getElementById('kBtnSetujui').className = 'rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition-all hover:border-green-300 hover:bg-green-50 hover:text-green-700';
}

function cekWarningDurasi() {
    const mulai = document.getElementById('kJamMulai').value;
    const selesai = document.getElementById('kJamSelesai').value;
    const warning = document.getElementById('warningDurasi');
    const text = document.getElementById('warningText');

    if (!mulai || !selesai) {
        warning.classList.add('hidden');
        return;
    }

    const [jm, mm] = mulai.split(':').map(Number);
    const [js, ms] = selesai.split(':').map(Number);
    const totalMenit = (js * 60 + ms) - (jm * 60 + mm);

    if (totalMenit <= 0) {
        warning.classList.add('hidden');
        return;
    }

    const jam = Math.floor(totalMenit / 60);

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
    if (!keputusan) {
        alert('Pilih keputusan terlebih dahulu.');
        return;
    }

    const jamMulai = document.getElementById('kJamMulai').value;
    const jamSelesai = document.getElementById('kJamSelesai').value;
    const catatan = document.getElementById('kCatatan').value;

    if (!jamMulai || !jamSelesai) {
        alert('Jam mulai dan jam selesai wajib diisi.');
        return;
    }

    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    fetch(`/ketua-tim/pengajuan/${currentId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            status: keputusan,
            jam_mulai_disetujui: jamMulai,
            jam_selesai_disetujui: jamSelesai,
            note: catatan,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) return;

        const approvedMulai = data.jam_mulai_disetujui ? data.jam_mulai_disetujui.substring(0, 5) : '-';
        const approvedSelesai = data.jam_selesai_disetujui ? data.jam_selesai_disetujui.substring(0, 5) : '-';

        const editIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        `;

        const badgeClass = keputusan === 'approved'
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-600';

        const badgeText = keputusan === 'approved' ? 'Disetujui' : 'Ditolak';

        const statusElement = document.getElementById(`status-${currentId}`);

        if (statusElement) {
            statusElement.innerHTML = `
                <div class="inline-flex items-center justify-center gap-2">
                    <span class="${badgeClass} whitespace-nowrap rounded-full px-2 py-0.5 text-xs">${badgeText}</span>
                    <button type="button" onclick="openModalKeputusan(${currentId}, '${approvedMulai}', '${approvedSelesai}')" class="cursor-pointer text-gray-400 hover:text-gray-600">
                        ${editIcon}
                    </button>
                </div>
            `;
        }

        const jamDisetujuiElement = document.getElementById(`jam-disetujui-${currentId}`);

        if (jamDisetujuiElement) {
            jamDisetujuiElement.textContent = keputusan === 'approved'
                ? `${approvedMulai} - ${approvedSelesai}`
                : '-';
        }

        closeModalKeputusan();
    })
    .catch(() => {
        alert('Gagal menyimpan, coba lagi.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Simpan';
    });
};
</script>
@endpush

@endsection
