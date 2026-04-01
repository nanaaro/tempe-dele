@extends('layouts.app')

@section('title', 'Pengajuan Lembur')

@section('content')

<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10">

    {{-- Flash success --}}
    @if(session('success'))
    <div class="mt-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif


    <div class="overflow-visible">

        <div class="flex items-center gap-3 max-w-7xl mx-auto my-5">

            {{-- Filter Periode --}}
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
                            <button type="button" id="btnToday" class="text-sm font-medium text-gray-500 hover:text-[#faa938]">Hari ini</button>
                            <button type="button" id="btnDateClose" class="px-3 py-1 text-sm font-medium rounded-full border border-gray-200 text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">Tutup</button>
                        </div>
                    </div>
                </div>

            {{-- Filter Tim --}}
                <div class="relative shrink-0">
                    <input type="text" id="searchTim" placeholder="Cari nama tim..."
                        onclick="toggleDropdownTim()" oninput="filterDropdownTim()" autocomplete="off"
                        class="w-120 h-10 rounded-full border border-gray-200 bg-white pl-4 pr-8 text-sm text-gray-700 focus:border-[#faa938] focus:outline-none focus:ring-2 focus:ring-[#faa938]/20"/>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-3 w-3 text-gray-400">
                            <path fill="currentColor" d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/>
                        </svg>
                    </div>
                    <div id="dropdownTim" class="hidden absolute z-10 mt-1 w-full max-h-48 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg">
                        <ul id="listTim"></ul>
                    </div>
                </div>

            {{-- Spacer atau devider --}}
            <div class="flex-1"></div>

            {{-- tombol ajukan--}}
                <a href="javascript:void(0)" id="btnAjukan"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full rounded-xl table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize rounded-tl-xl w-24">Tanggal</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize w-24">Jam Diajukan</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize w-24">Jam Disetujui</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize">Uraian Kegiatan</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize w-28">Ketua Tim</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize w-32">Nama Tim</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize w-20">Status</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-900 capitalize rounded-tr-xl w-24">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300" id="tabelLembur">
                    @forelse($transaksi as $t)
                    <tr class="bg-white transition-all duration-500 hover:bg-gray-50
                        data-tanggal="{{ $t->date }}"
                        data-tim="{{ $t->tim_kode_tim }}">
                        <td class="px-2 py-2 text-xs text-gray-900">
                            {{ \Carbon\Carbon::parse($t->date)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 text-center">
                            @if($t->jam_mulai && $t->jam_selesai)
                                {{ substr($t->jam_mulai,0,5) }} - {{ substr($t->jam_selesai,0,5) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 text-center">
                            @if($t->jam_mulai_disetujui && $t->jam_selesai_disetujui)
                                {{ substr($t->jam_mulai_disetujui,0,5) }} - {{ substr($t->jam_selesai_disetujui,0,5) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900">
                            {{ $t->uraian ?? '-' }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 text-left">
                            {{ $t->nama_ketua ?? '-' }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 text-left">
                            {{ $t->nama_tim ?? '-' }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 text-center">
                            @if($t->status === 'pending')
                                <span class="bg-amber-100 rounded-full px-2 text-xs text-amber-700 py-0.5">Diproses</span>
                            @elseif($t->status === 'approved')
                                <span class="bg-green-100 rounded-full px-2 text-xs text-green-700 py-0.5">Disetujui</span>
                            @elseif($t->status === 'rejected')
                                <span class="bg-red-100 rounded-full px-2 text-xs text-red-600 py-0.5">Ditolak</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 text-center">
                            {{ $t->note ?? '-' }}
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
    </div>

        {{-- Pagination --}}
        @if($transaksi->hasPages())
        <div class="flex justify-center mt-6">
            <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
                @if($transaksi->onFirstPage())
                    <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </span>
                @else
                    <a class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]" href="{{ $transaksi->previousPageUrl() }}">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </a>
                @endif

                <p class="text-gray-500 text-sm">Page {{ $transaksi->currentPage() }} of {{ $transaksi->lastPage() }}</p>

                @if($transaksi->hasMorePages())
                    <a class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]" href="{{ $transaksi->nextPageUrl() }}">
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
</div>

{{-- MODAL --}}
<div id="modalAjukan" class="fixed inset-0 z-50 hidden">
    <div id="modalOverlay" class="absolute inset-0 bg-black/40"></div>

    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Ajukan Lembur</h2>
                <button id="btnCloseModal" class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
            </div>

            <form action="{{ route('lembur.store') }}" method="POST" class="px-6 py-5 space-y-5">
                @csrf

                {{-- Hidden kode_tim — diisi JS saat pilih ketua --}}
                <input type="hidden" name="kode_tim" id="kode_tim">

                {{-- Ketua Tim --}}
                <div>
                    <label for="approver_id" class="block text-sm font-medium text-gray-700 mb-2">Ketua Tim</label>
                    <select id="approver_id" name="approver_id" required
                        class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                        <option value="">Pilih Ketua Tim</option>
                        @forelse($ketuaTim as $ketua)
                            <option value="{{ $ketua['nip'] }}" data-kode="{{ $ketua['kode_tim'] }}">
                                {{ $ketua['nama'] }} ({{ $ketua['tim'] }})
                            </option>
                        @empty
                            <option disabled>Kamu tidak terdaftar di tim manapun</option>
                        @endforelse
                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal"
                        class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                    <p id="infoHari" class="mt-1 text-xs text-gray-400 hidden"></p>
                </div>

                {{-- Jam --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" required
                            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                        <p id="infoJamMulai" class="mt-1 text-xs text-black hidden">Default hari kerja: 16:01</p>
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" required
                            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                    </div>
                </div>

                {{-- Preview durasi --}}
                <p id="previewDurasi" class="text-xs text-gray-500 hidden">
                    Estimasi: <span id="durasiLabel" class="font-semibold text-gray-800"></span>
                </p>

                {{-- Uraian --}}
                <div>
                    <label for="uraian" class="block text-sm font-medium text-gray-700 mb-2">Uraian Kegiatan</label>
                    <textarea id="uraian" name="uraian" rows="3" required
                        class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900"
                        placeholder="Contoh: Penyusunan laporan bulanan..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" id="btnCancel"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Modal
    const modal     = document.getElementById('modalAjukan');
    const btnAjukan = document.getElementById('btnAjukan');
    const btnClose  = document.getElementById('btnCloseModal');
    const btnCancel = document.getElementById('btnCancel');
    const overlay   = document.getElementById('modalOverlay');

    function openModal()  { modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
    function closeModal() { modal.classList.add('hidden');    document.body.classList.remove('overflow-hidden'); }

    btnAjukan.addEventListener('click', openModal);
    btnClose.addEventListener('click', closeModal);
    btnCancel.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);

    // Saat pilih ketua tim → isi hidden kode_tim
    document.getElementById('approver_id').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('kode_tim').value = selected.dataset.kode || '';
    });

    // Saat pilih tanggal → auto set jam mulai & info hari
    document.getElementById('tanggal').addEventListener('change', function () {
        const date      = new Date(this.value);
        const dayOfWeek = date.getUTCDay(); // 0=Minggu, 1=Sen, ..., 5=Jum, 6=Sab

        const isWeekend  = (dayOfWeek === 0 || dayOfWeek === 6);
        const isFriday   = (dayOfWeek === 5);
        const infoHari   = document.getElementById('infoHari');
        const infoMulai  = document.getElementById('infoJamMulai');
        const jamMulai   = document.getElementById('jam_mulai');

        infoHari.classList.remove('hidden');

        if (isWeekend) {
            infoHari.textContent  = '📅 Hari libur — jam mulai bebas';
            infoHari.className    = 'mt-1 text-xs text-black';
            infoMulai.classList.add('hidden');
            jamMulai.value        = '';
            jamMulai.removeAttribute('min');
        } else if (isFriday) {
            infoHari.textContent  = '📅 Hari Jumat — jam mulai default 16:31';
            infoHari.className    = 'mt-1 text-xs text-black';
            infoMulai.textContent = 'Default hari Jumat: 16:31';
            infoMulai.classList.remove('hidden');
            jamMulai.value        = '16:31';
            jamMulai.setAttribute('min', '16:31');
        } else {
            infoHari.textContent  = '📅 Hari kerja — jam mulai default 16:01';
            infoHari.className    = 'mt-1 text-xs text-black';
            infoMulai.textContent = 'Default hari kerja: 16:01';
            infoMulai.classList.remove('hidden');
            jamMulai.value        = '16:01';
            jamMulai.setAttribute('min', '16:01');
        }

        hitungDurasi();
    });

    // Preview durasi real-time
    function hitungDurasi() {
        const mulai   = document.getElementById('jam_mulai').value;
        const selesai = document.getElementById('jam_selesai').value;
        const preview = document.getElementById('previewDurasi');
        const label   = document.getElementById('durasiLabel');

        if (!mulai || !selesai) { preview.classList.add('hidden'); return; }

        const [jm, mm] = mulai.split(':').map(Number);
        const [js, ms] = selesai.split(':').map(Number);
        const totalMenit = (js * 60 + ms) - (jm * 60 + mm);

        if (totalMenit <= 0) { preview.classList.add('hidden'); return; }

        const jam   = Math.floor(totalMenit / 60);
        const menit = totalMenit % 60;
        label.textContent = `${jam} jam ${menit > 0 ? menit + ' menit' : ''} (dihitung ${jam} jam)`;
        preview.classList.remove('hidden');
    }

    document.getElementById('jam_mulai').addEventListener('change', hitungDurasi);
    document.getElementById('jam_selesai').addEventListener('change', hitungDurasi);

    // =====================
    // HELPER
    // =====================
    const now = new Date();
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
    // STATE FILTER
    // =====================
    let selectedDate = null;
    let selectedTim  = null;
    let cachedTim    = [];

    // =====================
    // FETCH TIM PEGAWAI
    // =====================
    fetch('/lembur/tim')
        .then(r => r.json())
        .then(data => {
            cachedTim = data;
            renderDropdownTim('');
        });

    function renderDropdownTim(filter) {
        const list = document.getElementById('listTim');
        list.innerHTML = '';

        const liSemua = document.createElement('li');
        liSemua.className = 'cursor-pointer px-4 py-2 text-sm text-gray-400 hover:bg-gray-50';
        liSemua.textContent = 'Semua tim';
        liSemua.onclick = () => pilihTim(null);
        list.appendChild(liSemua);

        cachedTim
            .filter(t => t.nama_tim.toLowerCase().includes(filter.toLowerCase()))
            .forEach(tim => {
                const li = document.createElement('li');
                li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
                li.textContent = tim.nama_tim;
                li.onclick = () => pilihTim(tim);
                list.appendChild(li);
            });
    }

    window.toggleDropdownTim = function () {
        document.getElementById('dropdownTim').classList.toggle('hidden');
        renderDropdownTim('');
    };

    window.filterDropdownTim = function () {
        const search = document.getElementById('searchTim').value;
        renderDropdownTim(search);
        document.getElementById('dropdownTim').classList.remove('hidden');
    };

    function pilihTim(tim) {
        selectedTim = tim ? tim.kode_tim : null;
        document.getElementById('searchTim').value = tim ? tim.nama_tim : '';
        document.getElementById('dropdownTim').classList.add('hidden');
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

        let view      = 'day';
        let viewYear  = now.getFullYear();
        let viewMonth = now.getMonth();
        let selYear   = null;
        let selMonth  = null;
        let selDay    = null;

        function setDate(y, m, d) {
            selYear = y; selMonth = m; selDay = d;
            selectedDate = `${y}-${pad2(m + 1)}-${pad2(d)}`;
            dateLabel.textContent = `${d} ${monthShort[m]} ${y}`;
            dateValue.value = selectedDate;
            filterTabel();
            updateResetBtn();
        }

        function openPanel() {
            viewYear  = now.getFullYear();
            viewMonth = now.getMonth();
            renderDay();
            panel.classList.remove('hidden');
        }

        function closePanel() { panel.classList.add('hidden'); }

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

            for (let i = firstDay - 1; i >= 0; i--) {
                const d = daysInPrev - i;
                dayGrid.appendChild(makeBtn(d, base + 'border-transparent text-gray-300', () => {
                    let m = viewMonth - 1, y = viewYear;
                    if (m < 0) { m = 11; y--; }
                    setDate(y, m, d); viewYear = y; viewMonth = m; renderDay();
                }));
            }

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

            const total     = firstDay + daysInMonth;
            const remaining = total % 7 === 0 ? 0 : 7 - (total % 7);
            for (let d = 1; d <= remaining; d++) {
                const _d = d;
                dayGrid.appendChild(makeBtn(_d, base + 'border-transparent text-gray-300', () => {
                    let m = viewMonth + 1, y = viewYear;
                    if (m > 11) { m = 0; y++; }
                    setDate(y, m, _d); viewYear = y; viewMonth = m; renderDay();
                }));
            }

            grid.appendChild(dayGrid);
        }

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
                const cls = isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isNow ? 'border-[#faa938] text-[#faa938] bg-white'
                    : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]';
                g.appendChild(makeBtn(name.slice(0,3), 'px-2 py-2 text-sm rounded-lg border transition ' + cls, () => {
                    viewMonth = m; renderDay();
                }));
            });
            grid.appendChild(g);
        }

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
                const cls = isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isNow ? 'border-[#faa938] text-[#faa938] bg-white'
                    : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]';
                const _y = y;
                g.appendChild(makeBtn(_y, 'px-2 py-2 text-sm rounded-lg border transition ' + cls, () => {
                    viewYear = _y; renderMonth();
                }));
            }
            grid.appendChild(g);
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.contains('hidden') ? openPanel() : closePanel();
        });

        navLabel.addEventListener('click', (e) => {
            e.stopPropagation();
            if (view === 'day') renderMonth();
            else if (view === 'month') renderYear();
        });

        btnPrev?.addEventListener('click', (e) => {
            e.stopPropagation();
            if (view === 'day') { viewMonth--; if (viewMonth < 0) { viewMonth = 11; viewYear--; } renderDay(); }
            else if (view === 'month') { viewYear--; renderMonth(); }
            else if (view === 'year') { viewYear -= 12; renderYear(); }
        });

        btnNext?.addEventListener('click', (e) => {
            e.stopPropagation();
            if (view === 'day') { viewMonth++; if (viewMonth > 11) { viewMonth = 0; viewYear++; } renderDay(); }
            else if (view === 'month') { viewYear++; renderMonth(); }
            else if (view === 'year') { viewYear += 12; renderYear(); }
        });

        btnToday?.addEventListener('click', (e) => {
            e.stopPropagation();
            viewYear = now.getFullYear(); viewMonth = now.getMonth();
            setDate(now.getFullYear(), now.getMonth(), now.getDate());
            closePanel();
        });

        btnClose?.addEventListener('click', (e) => { e.stopPropagation(); closePanel(); });

        document.addEventListener('click', (e) => {
            if (!picker.contains(e.target)) closePanel();
            if (!document.getElementById('dropdownTim')?.contains(e.target) &&
                !document.getElementById('searchTim')?.contains(e.target)) {
                document.getElementById('dropdownTim')?.classList.add('hidden');
            }
        });
    })();

    // =====================
    // FILTER TABEL
    // =====================
    function filterTabel() {
        document.querySelectorAll('#tabelLembur tr').forEach(row => {
            const cocokTanggal = !selectedDate || row.dataset.tanggal === selectedDate;
            const cocokTim     = !selectedTim  || row.dataset.tim === selectedTim;
            row.style.display  = (cocokTanggal && cocokTim) ? '' : 'none';
        });
    }

    function updateResetBtn() {
        const btn = document.getElementById('btnResetFilter');
        if (!btn) return;
        (selectedDate || selectedTim) ? btn.classList.remove('hidden') : btn.classList.add('hidden');
    }

    document.getElementById('btnResetFilter')?.addEventListener('click', () => {
        selectedDate = null;
        selectedTim  = null;
        document.getElementById('dateLabel').textContent = 'Semua Tanggal';
        document.getElementById('dateValue').value = '';
        document.getElementById('searchTim').value = '';
        filterTabel();
        updateResetBtn();
    });
</script>
@endpush

@endsection
