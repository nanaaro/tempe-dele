@extends('layouts.app')

@section('title', 'Presensi Pegawai')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="{ open: false, detail: null }"
     x-on:presensi-detail.window="
        open = true;
        detail = $event.detail;
     ">

    <div class="flex items-center gap-2">

        {{-- Filter Periode --}}
        <div class="relative shrink-0" id="periodPicker">
            <button type="button" id="periodBtn"
                class="inline-flex items-center h-10 gap-2 px-4 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-xl hover:border-[#faa938] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                    <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
                </svg>
                <span id="periodLabel" class="leading-none">Mar 2026</span>
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

        {{-- Spacer + Divider --}}
        <div class="flex-1"></div>
        <div class="h-6 self-center border-l border-gray-200"></div>

        {{-- Riwayat Upload --}}
        <a href="{{ route('admin.riwayat_presensi') }}" title="Riwayat Upload"
            class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:border-[#faa938] hover:text-[#faa938] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </a>

        {{-- Upload --}}
        <button type="button" title="Upload Presensi" onclick="openUploadModal()"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
        </button>

    </div>

    {{-- Modal detail presensi (butuh Alpine, tetap di dalam scope) --}}
    <div x-show="open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none;">
        <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl ring-1 ring-gray-200">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500" x-text="detail?.date ?? '-'"></div>
                    <div class="text-lg font-semibold text-gray-900">Detail Presensi</div>
                </div>
                <button class="h-9 w-9 rounded-xl hover:bg-gray-50" @click="open=false">✕</button>
            </div>

            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Status</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.status ?? '-'"></div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Sumber</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.source ?? 'Upload Admin'"></div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Masuk</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.checkIn ?? '-'"></div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Pulang</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.checkOut ?? '-'"></div>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 flex justify-end gap-2">
                <button class="h-10 px-4 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm"
                        @click="open=false">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
{{-- ↑ Alpine scope BERAKHIR di sini ↑ --}}

{{-- Calendar card DI LUAR Alpine supaya tidak di-reset Alpine --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-3 sm:p-4">
        <div id="presensiGrid"></div>
    </div>
</div>

{{-- Modal Upload DI LUAR Alpine --}}
<div id="modalUpload" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">Upload Presensi</h2>
            <button onclick="closeUploadModal()" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="uploadDropzone"
            class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-[#faa938] transition-colors"
            onclick="document.getElementById('fileInput').click()"
            ondragover="event.preventDefault(); this.classList.add('border-[#faa938]')"
            ondragleave="this.classList.remove('border-[#faa938]')"
            ondrop="handleDrop(event)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <p class="text-sm text-gray-500">Drag & drop file xlsx atau <span class="text-[#faa938] font-medium">browse</span></p>
            <p id="fileName" class="text-xs text-gray-400 mt-1">Belum ada file dipilih</p>
            <input type="file" id="fileInput" accept=".xlsx,.xls" class="hidden" onchange="handleFileSelect(this)"/>
        </div>

        <div id="uploadAlert" class="hidden mt-3 rounded-xl px-4 py-3 text-sm font-medium"></div>

        <div class="flex justify-end gap-2 mt-5">
            <button onclick="closeUploadModal()" class="px-4 py-2 rounded-lg text-sm text-gray-600 border border-gray-200 hover:bg-gray-50">Batal</button>
            <button onclick="submitUpload()" class="px-4 py-2 rounded-lg text-sm text-white bg-[#faa938] hover:brightness-95">Upload</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // =====================
    // GLOBAL STATE
    // =====================
    const now = new Date();
    let current = new Date(now.getFullYear(), now.getMonth(), 1);
    let selectedEmployeeId = null;
    let cachedTim = [];
    let cachedPegawai = [];
    let selYear  = now.getFullYear();
    let selMonth = now.getMonth();
    let presensiData = {};
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

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
    // FETCH DATA
    // =====================
    async function fetchTim() {
        const res = await fetch('/admin/presensi/tim');
        cachedTim = await res.json();
    }

    async function fetchPegawai(kodeTim = '') {
        const url = kodeTim
            ? `/admin/presensi/pegawai?kode_tim=${kodeTim}`
            : '/admin/presensi/pegawai';
        const res = await fetch(url);
        cachedPegawai = await res.json();
        populateDropdown('', cachedPegawai);
    }

    // =====================
    // DROPDOWN PEGAWAI
    // =====================
    function populateDropdown(filter = '', data = []) {
        const list = document.getElementById('listPegawai');
        list.innerHTML = '';
        data
            .filter(e => `${e.nama} ${e.nip}`.toLowerCase().includes(filter.toLowerCase()))
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

    window.pilihPegawai = function (emp) {
        selectedEmployeeId = emp.id_pegawai;
        document.getElementById('searchPegawai').value = `${emp.nama} - ${emp.nip}`;
        document.getElementById('dropdownPegawai').classList.add('hidden');
        fetchPresensi();
    };

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

        if (!picker || !btn || !panel) return;

        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        let view     = 'month';
        let viewYear = now.getFullYear();

        function setPeriod(y, m) {
            selYear = y; selMonth = m;
            periodLabel.textContent = `${monthShort[m]} ${y}`;
            periodValue.value = `${y}-${pad2(m + 1)}`;
            current = new Date(y, m, 1);
            if (selectedEmployeeId) {
                fetchPresensi();
            } else {
                render();
            }
        }

        function renderMonth() {
            view = 'month';
            navLabel.textContent = String(viewYear);
            navLabel.className = 'text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none';
            grid.innerHTML = '';
            monthNames.forEach((name, m) => {
                const isSelected = (m === selMonth && viewYear === selYear);
                const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                    :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
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
            navLabel.className = 'text-sm font-medium text-gray-400 select-none cursor-default';
            grid.innerHTML = '';
            for (let y = startYear; y < startYear + 12; y++) {
                const isSelected = (y === selYear);
                const isNow      = (y === now.getFullYear());
                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                    :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );
                const _y = y;
                grid.appendChild(makeBtn(_y, cls, () => { viewYear = _y; renderMonth(); }));
            }
        }

        function navigate(dir) {
            if (view === 'month') { viewYear += dir; renderMonth(); }
            else if (view === 'year') { viewYear += dir * 12; renderYear(); }
        }

        function openPanel() {
            viewYear = selYear;
            renderMonth();
            panel.classList.remove('hidden');
        }

        function closePanel() { panel.classList.add('hidden'); }

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

        setPeriod(selYear, selMonth);
    })();

    // =====================
    // UPLOAD MODAL
    // =====================
    let selectedFile = null;

    window.openUploadModal = function () {
        selectedFile = null;
        document.getElementById('fileName').textContent = 'Belum ada file dipilih';
        document.getElementById('uploadAlert').classList.add('hidden');
        const modal = document.getElementById('modalUpload');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeUploadModal = function () {
        const modal = document.getElementById('modalUpload');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    window.handleFileSelect = function (input) {
        if (input.files[0]) {
            selectedFile = input.files[0];
            document.getElementById('fileName').textContent = selectedFile.name;
        }
    };

    window.handleDrop = function (e) {
        e.preventDefault();
        document.getElementById('uploadDropzone').classList.remove('border-[#faa938]');
        const file = e.dataTransfer.files[0];
        if (file && (file.name.endsWith('.xlsx') || file.name.endsWith('.xls'))) {
            selectedFile = file;
            document.getElementById('fileName').textContent = file.name;
        }
    };

    function showUploadAlert(message, type = 'success') {
        const el = document.getElementById('uploadAlert');
        el.textContent = message;
        el.className = type === 'success'
            ? 'mt-3 rounded-xl px-4 py-3 text-sm font-medium bg-green-50 text-green-700 border border-green-200'
            : 'mt-3 rounded-xl px-4 py-3 text-sm font-medium bg-red-50 text-red-700 border border-red-200';
        el.classList.remove('hidden');
    }

    window.submitUpload = async function () {
        if (!selectedFile) {
            showUploadAlert('Pilih file xlsx terlebih dahulu.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('file', selectedFile);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            showUploadAlert('Sedang memproses...');
            const res = await fetch('/admin/presensi/upload', {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();
            if (res.ok) {
                showUploadAlert(data.message ?? 'Upload berhasil!');
                setTimeout(() => closeUploadModal(), 1500);
            } else {
                showUploadAlert(data.message ?? 'Gagal upload.', 'error');
            }
        } catch {
            showUploadAlert('Gagal menghubungi server.', 'error');
        }
    };

    document.getElementById('modalUpload')?.addEventListener('click', function (e) {
        if (e.target === this) closeUploadModal();
    });

    // =====================
    // FETCH PRESENSI
    // =====================
    async function fetchPresensi() {
        if (!selectedEmployeeId) return;

        const year  = current.getFullYear();
        const month = String(current.getMonth() + 1).padStart(2, '0');
        const pegawai = cachedPegawai.find(p => p.id_pegawai === selectedEmployeeId);
        const niplama = pegawai?.nip_lama ?? '';

        if (!niplama) return;

        const url = `/admin/presensi/kalender?niplama=${niplama}&periode=${year}-${month}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        presensiData = await res.json();
        render();
    }

    // =====================
    // RENDER KALENDER
    // =====================
    function statusColor(st) {
        return {
            WFO:  'text-green-600',
            WFA:  'text-blue-600',
            WFOL: 'text-sky-600',
            DL:   'text-amber-600',
            CUTI: 'text-rose-600',
            KN:   'text-gray-500',
        }[st] || 'text-gray-700';
    }

    function fmt(d) {
        const y   = d.getFullYear();
        const m   = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function render() {
        const gridEl = document.getElementById('presensiGrid');
        if (!gridEl) return;

        const year  = current.getFullYear();
        const month = current.getMonth();

        const start = new Date(year, month, 1);
        start.setDate(start.getDate() - ((start.getDay() + 6) % 7));

        const end = new Date(year, month + 1, 0);
        end.setDate(end.getDate() + (6 - (end.getDay() + 6) % 7));

        const today = fmt(new Date());
        const days = [];
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            days.push(new Date(d));
        }

        const dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        gridEl.innerHTML = `
            <div class="grid grid-cols-7 gap-px rounded-2xl overflow-hidden ring-1 ring-gray-200 bg-gray-200">
                ${dayNames.map((n, i) => `
                    <div class="bg-white px-4 py-3 text-xs font-semibold ${i === 6 ? 'text-rose-600' : 'text-gray-600'}">
                        ${n}
                    </div>
                `).join('')}
                ${days.map(d => {
                    const key     = fmt(d);
                    const inMonth = d.getMonth() === month;
                    const isSun   = ((d.getDay() + 6) % 7) === 6;
                    const p       = presensiData[key];

                    const statusHtml = p
                        ? `<div class="mt-1 text-xs font-medium text-center ${statusColor(p.status)}">${p.status}</div>
                           <div class="text-xs text-gray-400 text-center">${p.jam_mulai   ? p.jam_mulai.split(' ')[1].slice(0,8)   : '—'}</div>
                           <div class="text-xs text-gray-400 text-center">${p.jam_selesai ? p.jam_selesai.split(' ')[1].slice(0,8) : '—'}</div>`
                        : `<div class="flex-1 flex items-center justify-center text-xs text-gray-300 select-none">—</div>`;

                    return `
                        <button type="button" data-date="${key}" data-presensi='${p ? JSON.stringify(p) : ""}'
                            class="bg-white px-4 py-3 text-left min-h-[108px] ${p ? 'hover:bg-gray-50 cursor-pointer' : 'cursor-default'} transition flex flex-col
                                ${inMonth ? '' : 'opacity-40'} focus:outline-none">
                            <div class="text-sm font-semibold">
                                ${key === today
                                    ? `<span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#faa938] text-white">${d.getDate()}</span>`
                                    : `<span class="${isSun ? 'text-rose-600' : 'text-gray-900'}">${d.getDate()}</span>`
                                }
                            </div>
                            ${statusHtml}
                        </button>
                    `;
                }).join('')}
            </div>
        `;
    }

    function dispatchDetail(date, p) {
        window.dispatchEvent(new CustomEvent('presensi-detail', {
            detail: {
                date,
                status:   p.status,
                checkIn:  p.jam_mulai   ? p.jam_mulai.split(' ')[1].slice(0,5)   : '—',
                checkOut: p.jam_selesai ? p.jam_selesai.split(' ')[1].slice(0,5) : '—',
            }
        }));
    }

    // =====================
    // INIT
    // =====================
    document.addEventListener('DOMContentLoaded', function () {
        fetchTim();
        fetchPegawai();
        render();

        document.getElementById('presensiGrid').addEventListener('click', function (e) {
            const btn = e.target.closest('button[data-presensi]');
            if (!btn || !btn.dataset.presensi) return;
            const p    = JSON.parse(btn.dataset.presensi);
            const date = btn.dataset.date;
            if (p && date) dispatchDetail(date, p);
        });

        document.addEventListener('click', function (e) {
            const wrapperPegawai = document.getElementById('searchPegawai')?.closest('.relative');
            if (wrapperPegawai && !wrapperPegawai.contains(e.target))
                document.getElementById('dropdownPegawai').classList.add('hidden');
        });
    });
</script>

@endpush
