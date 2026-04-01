@extends('layouts.app')

@section('title', 'Daftar Hadir')

@section('content')

    <div class="flex items-center gap-3 max-w-7xl mx-auto px-8 my-5">

        {{-- Filter Periode --}}
        <div class="relative shrink-0" id="datePicker">
            <button type="button" id="dateBtn"
                class="inline-flex items-center h-10 gap-2 px-4 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-xl hover:border-[#faa938] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                    <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
                </svg>
                <span id="dateLabel" class="leading-none">17 Mar 2026</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-50">
                    <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
                </svg>
            </button>
            <input type="hidden" id="dateValue" name="date" value="">

            <div id="datePanel" class="hidden absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg p-3">

                {{-- Header navigasi bulan/tahun --}}
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

                {{-- View: grid tanggal --}}
                <div id="dateGrid"></div>

                {{-- Footer --}}
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

    {{-- Spacer atau devider --}}
    <div class="flex-1"></div>

    {{-- tombol upload --}}
        <button type="button" title="Upload Presensi"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
        </button>

    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto max-w-7xl mx-auto px-8 my-5">
        <table class="min-w-full rounded-xl border-separate border-spacing-0">
            <thead>
                <tr class="bg-gray-50">
                    <th rowspan="2" class="px-2 py-1 text-center text-sm font-semibold text-gray-900 border border-gray-100 rounded-tl-xl">Tanggal</th>
                    <th rowspan="2" class="px-2 py-1 text-center text-sm font-semibold text-gray-900 border border-gray-100">No</th>
                    <th rowspan="2" class="px-2 py-1 text-center text-sm font-semibold text-gray-900 border border-gray-100">Nama / NIP</th>
                    <th colspan="2" class="px-2 py-1 text-center text-sm font-semibold text-gray-900 border border-gray-100">Jam</th>
                    <th rowspan="2" class="px-2 py-1 text-center text-sm font-semibold text-gray-900 border border-gray-100 rounded-tr-xl">Tanda Tangan</th>
                </tr>
                <tr class="bg-gray-50">
                    <th class="p-4 text-center text-sm font-semibold text-gray-900 border border-gray-100">Datang</th>
                    <th class="p-4 text-center text-sm font-semibold text-gray-900 border border-gray-100">Pulang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($daftarHadir as $i => $d)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="p-4 text-sm text-gray-900 text-center border border-gray-100">
                        @if($i == 0 || $d->date != $daftarHadir[$i-1]->date)
                            {{ \Carbon\Carbon::parse($d->date)->translatedFormat('d/m/Y') }}
                        @endif
                    </td>
                    <td class="p-4 text-sm text-gray-900 text-center border border-gray-100">{{ $i + 1 }}</td>
                    <td class="p-4 text-sm text-gray-900 border border-gray-100">
                        {{ $d->nama }}<br>
                        <span class="text-xs text-gray-400">{{ $d->nip }}</span>
                    </td>
                    <td class="p-4 text-sm text-gray-900 text-center border border-gray-100">
                        {{ $d->jam_mulai_disetujui ? substr($d->jam_mulai_disetujui, 0, 5) : '-' }}
                    </td>
                    <td class="p-4 text-sm text-gray-900 text-center border border-gray-100">
                        {{ $d->jam_selesai_disetujui ? substr($d->jam_selesai_disetujui, 0, 5) : '-' }}
                    </td>
                    <td class="p-4 border border-gray-100 h-10"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-sm text-gray-400">
                        Tidak ada data untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
            <!-- /Tabel -->
</div>

<script>
    // =====================
    // GLOBAL STATE
    // =====================
    const now = new Date();
    let current = new Date(now.getFullYear(), now.getMonth(), 1);
    let selectedEmployeeId = null;
    let selectedTeamId = null;
    let cachedTim = [];
    let cachedPegawai = [];
    let pickerYear = now.getFullYear();
    let pickerMonth = now.getMonth();
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const gridEl = document.getElementById('presensiGrid');

    // =====================
    // FETCH DATA
    // =====================
    async function fetchTim() {
        const res = await fetch('/admin/presensi/tim');
        cachedTim = await res.json();
        populateDropdownTim('', cachedTim);
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
    // DROPDOWN TIM
    // =====================
    function populateDropdownTim(filter = '', data = []) {
        const list = document.getElementById('listTim');
        list.innerHTML = '';
        data
            .filter(t => t.nama_tim.toLowerCase().includes(filter.toLowerCase()))
            .forEach(tim => {
                const li = document.createElement('li');
                li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
                li.textContent = tim.nama_tim;
                li.onclick = () => pilihTim(tim);
                list.appendChild(li);
            });
    }

    window.toggleDropdownTim = function() {
        document.getElementById('dropdownTim').classList.toggle('hidden');
        populateDropdownTim('', cachedTim);
    };

    window.filterDropdownTim = function() {
        const search = document.getElementById('searchTim').value;
        populateDropdownTim(search, cachedTim);
        document.getElementById('dropdownTim').classList.remove('hidden');
    };

    window.pilihTim = function(tim) {
        selectedTeamId = tim.kode_tim;
        document.getElementById('searchTim').value = tim.nama_tim;
        document.getElementById('dropdownTim').classList.add('hidden');
        // Reset pegawai
        selectedEmployeeId = null;
        document.getElementById('searchPegawai').value = '';
        fetchPegawai(tim.kode_tim);
    };

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
    };

    document.addEventListener('DOMContentLoaded', function () {
    fetchTim();
    fetchPegawai();
});

    document.addEventListener('DOMContentLoaded', function () {
        fetchTim();
        fetchPegawai();

    document.addEventListener('click', function (e) {
        const wrapperTim = document.getElementById('searchTim')?.closest('.relative');
        if (wrapperTim && !wrapperTim.contains(e.target))
            document.getElementById('dropdownTim').classList.add('hidden');

        const wrapperPegawai = document.getElementById('searchPegawai')?.closest('.relative');
        if (wrapperPegawai && !wrapperPegawai.contains(e.target))
            document.getElementById('dropdownPegawai').classList.add('hidden');
    });
});

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
        let view = 'day'; // 'day' | 'month' | 'year'
        let viewYear  = now.getFullYear();
        let viewMonth = now.getMonth();
        let selYear   = now.getFullYear();
        let selMonth  = now.getMonth();
        let selDay    = now.getDate();

        function pad2(n) { return String(n).padStart(2, '0'); }

        function setDate(y, m, d) {
            selYear = y; selMonth = m; selDay = d;
            dateLabel.textContent = `${d} ${monthShort[m]} ${y}`;
            dateValue.value = `${y}-${pad2(m + 1)}-${pad2(d)}`;
            // TODO: trigger fetch/filter pakai dateValue.value
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
                const isSelected = (d === selDay && viewMonth === selMonth && viewYear === selYear);
                const isToday    = (d === now.getDate() && viewMonth === now.getMonth() && viewYear === now.getFullYear());
                const cls = isSelected
                    ? 'bg-[#faa938] text-white border-[#faa938]'
                    : isToday
                        ? 'border-[#faa938] text-[#faa938] bg-white'
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
                const b = makeBtn(name.slice(0, 3), 'px-2 py-2 text-sm rounded-lg border transition ' + cls, () => {
                    viewMonth = m;
                    renderDay();
                }, false);
                g.appendChild(b);
            });

            grid.appendChild(g);
        }

        // ---- RENDER YEAR ----
        function renderYear() {
            view = 'year';
            // Tampilkan range 12 tahun
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
                const b = makeBtn(y, 'px-2 py-2 text-sm rounded-lg border transition ' + cls, () => {
                    viewYear = y;
                    renderMonth();
                }, false);
                g.appendChild(b);
            }

            grid.appendChild(g);
        }

        // ---- HELPER ----
        function makeBtn(text, cls, onClick) {
            const b = document.createElement('button');
            b.type = 'button';
            b.textContent = text;
            b.className = cls;
            b.addEventListener('click', (e) => {
                e.stopPropagation();
                onClick();
            });
            return b;
        }

        // ---- NAV PREV/NEXT ----
        function navigate(dir) {
            if (view === 'day') {
                viewMonth += dir;
                if (viewMonth < 0)  { viewMonth = 11; viewYear--; }
                if (viewMonth > 11) { viewMonth = 0;  viewYear++; }
                renderDay();
            } else if (view === 'month') {
                viewYear += dir;
                renderMonth();
            } else if (view === 'year') {
                const startYear = Math.floor(viewYear / 12) * 12;
                viewYear = startYear + (dir * 12);
                renderYear();
            }
        }

        function openPanel() {
            viewYear = selYear; viewMonth = selMonth;
            renderDay();
            panel.classList.remove('hidden');
        }

        function closePanel() { panel.classList.add('hidden'); }

        // ---- EVENT LISTENERS ----
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.contains('hidden') ? openPanel() : closePanel();
        });

        navLabel.addEventListener('click', (e) => {
            e.stopPropagation();
            if (view === 'day')   renderMonth();
            else if (view === 'month') renderYear();
            // view === 'year' -> tidak naik lagi
        });

        btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); navigate(-1); });
        btnNext?.addEventListener('click', (e) => { e.stopPropagation(); navigate(1); });

        btnToday?.addEventListener('click', (e) => {
            e.stopPropagation();
            viewYear = now.getFullYear(); viewMonth = now.getMonth();
            setDate(now.getFullYear(), now.getMonth(), now.getDate());
            closePanel();
        });

        btnClose?.addEventListener('click', (e) => { e.stopPropagation(); closePanel(); });

        document.addEventListener('click', (e) => {
            if (!picker.contains(e.target)) closePanel();
        });

        // Init
        setDate(selYear, selMonth, selDay);
    })();
    </script>

@endsection
