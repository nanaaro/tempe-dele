@extends('layouts.app')

@section('title', 'Pesensi Pegawai')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="{ open: false, detail: null }"
     x-on:presensi-detail.window="
        open = true;
        detail = $event.detail;
     ">

    <!-- Header + Filter -->
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">

        <!-- Left: Filter + Download -->
        <div class="flex flex-col sm:flex-row sm:items-end gap-3 w-full xl:w-auto">

            <!-- Filter Pegawai -->
            <div class="w-full sm:w-[320px]">
                <label for="pegawaiFilter" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Pegawai
                </label>
                <select id="pegawaiFilter"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-700 shadow-sm transition focus:border-[#faa938] focus:outline-none focus:ring-2 focus:ring-[#faa938]/20">
                    <option value="">Pilih nama - NIP</option>
                </select>
            </div>

            <!-- Filter Bulan -->
            <div class="w-full sm:w-47.5">
                <label for="monthFilter" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Periode
                </label>
                <input id="monthFilter" type="month"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-700 shadow-sm transition focus:border-[#faa938] focus:outline-none focus:ring-2 focus:ring-[#faa938]/20">
            </div>

            <!-- Upload -->
            <div class="relative shrink-0" id="downloadMenu">
                <label class="mb-1.5 block text-sm font-medium text-transparent select-none">
                    Aksi
                </label>
                <button type="button" id="downloadBtn"
                    class="inline-flex h-11 items-center gap-2 rounded-xl border border-[#faa938] bg-white px-4 text-sm font-medium text-[#d98b18] shadow-sm transition hover:bg-[#fff7ed]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-4 w-4 fill-current">
                        <path d="M352 173.3L352 384C352 401.7 337.7 416 320 416C302.3 416 288 401.7 288 384L288 173.3L246.6 214.7C234.1 227.2 213.8 227.2 201.3 214.7C188.8 202.2 188.8 181.9 201.3 169.4L297.3 73.4C309.8 60.9 330.1 60.9 342.6 73.4L438.6 169.4C451.1 181.9 451.1 202.2 438.6 214.7C426.1 227.2 405.8 227.2 393.3 214.7L352 173.3zM320 464C364.2 464 400 428.2 400 384L480 384C515.3 384 544 412.7 544 448L544 480C544 515.3 515.3 544 480 544L160 544C124.7 544 96 515.3 96 480L96 448C96 412.7 124.7 384 160 384L240 384C240 428.2 275.8 464 320 464zM464 488C477.3 488 488 477.3 488 464C488 450.7 477.3 440 464 440C450.7 440 440 450.7 440 464C440 477.3 450.7 488 464 488z"/>
                    </svg>
                    <span>Unduh</span>
                </button>

                <div id="downloadPanel"
                    class="hidden absolute left-0 sm:left-auto sm:right-0 z-50 mt-2 w-56 rounded-xl border border-gray-200 bg-white p-2 shadow-lg">
                    <a id="downloadSpkl" href="#"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-50">
                        <span class="font-medium">SPKL</span>
                        <span class="ml-auto text-xs text-gray-400">PDF</span>
                    </a>
                    <a id="downloadLembur" href="#"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-50">
                        <span class="font-medium">Laporan Lembur</span>
                        <span class="ml-auto text-xs text-gray-400">PDF</span>
                    </a>
                    <div class="my-2 border-t border-gray-100"></div>
                    <p id="downloadHint" class="px-3 pb-1 text-xs text-gray-500">
                        Pilih periode dulu
                    </p>
                </div>
            </div>

        </div>

        <!-- Right: Calendar Navigation -->
        <div class="flex items-center justify-between sm:justify-end gap-2 rounded-2xl bg-white px-3 py-2 shadow-sm ring-1 ring-gray-200">
            <div id="rangeText" class="px-2 text-sm font-semibold text-gray-700"></div>

            <div class="flex items-center gap-2">
                <button id="btnToday"
                    class="h-9 rounded-xl bg-[#faa938] px-4 text-sm font-medium text-black transition hover:brightness-95">
                    Hari ini
                </button>

                <button id="btnPrev"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50">
                    <span class="sr-only">Prev</span>
                    ‹
                </button>

                <button id="btnNext"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    ›
                </button>
            </div>
        </div>

    </div>

    <!-- Legend -->
    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
        <span class="ml-auto text-xs text-gray-500">Klik tanggal/event untuk lihat detail</span>
    </div>

    <!-- Calendar card -->
    <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-3 sm:p-4">
        <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-3 sm:p-4">
            <div id="presensiGrid"></div>
        </div>
    </div>

    <!-- Modal detail -->
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
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const gridEl = document.getElementById('presensiGrid');
    const rangeEl = document.getElementById('rangeText');

    const pegawaiFilter = document.getElementById('pegawaiFilter');
    const monthFilter = document.getElementById('monthFilter');

    const selectedPeriod = document.getElementById('selectedPeriod');

    const now = new Date();
    let current = new Date(now.getFullYear(), now.getMonth(), 1);

    // dummy data pegawai
    const employees = [
        { id: '1', name: 'Budi Santoso', nip: '12345' },
        { id: '2', name: 'Ayu Lestari', nip: '67890' },
        { id: '3', name: 'Budi Santoso', nip: '54321' },
    ];

    // dummy data presensi per pegawai
    const attendanceData = {
        '1': {
            '2026-02-01': { in:'07:10:21', out:'17:16:45', status:'WFO', note:'' },
            '2026-02-02': { in:'07:01:40', out:'16:10:28', status:'WFO', note:'' },
            '2026-02-03': { in:'07:12:23', out:'17:23:41', status:'WFOL', note:'' },
            '2026-02-08': { in:'-', out:'-', status:'CUTI', note:'' },
            '2026-02-14': { in:'-', out:'-', status:'KN', note:'Tidak ada presensi' },
        },
        '2': {
            '2026-02-02': { in:'08:01:00', out:'17:02:00', status:'WFA', note:'' },
            '2026-02-05': { in:'07:45:00', out:'17:20:00', status:'WFO', note:'' },
            '2026-02-11': { in:'-', out:'-', status:'DL', note:'Perjalanan dinas' },
        },
        '3': {
            '2026-02-03': { in:'07:05:33', out:'17:00:12', status:'WFO', note:'' },
            '2026-02-07': { in:'-', out:'-', status:'CUTI', note:'' },
        }
    };

    function populateEmployeeFilter() {
        employees.forEach(emp => {
            const option = document.createElement('option');
            option.value = emp.id;
            option.textContent = `${emp.name} - ${emp.nip}`;
            pegawaiFilter.appendChild(option);
        });
    }

    function statusColor(st) {
        return {
            WFO: 'text-green-600',
            WFA: 'text-blue-600',
            WFOL: 'text-sky-600',
            DL: 'text-amber-600',
            CUTI: 'text-rose-600',
            KN: 'text-gray-500',
        }[st] || 'text-gray-700';
    }

    function fmt(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function formatMonthLabel(date) {
        return new Intl.DateTimeFormat('id-ID', {
            month: 'long',
            year: 'numeric'
        }).format(date);
    }

    function getSelectedEmployee() {
        return employees.find(emp => emp.id === pegawaiFilter.value) || null;
    }

    function getCurrentEmployeeData() {
        if (!pegawaiFilter.value) return {};
        return attendanceData[pegawaiFilter.value] || {};
    }

    function syncMonthInput() {
        monthFilter.value = `${current.getFullYear()}-${String(current.getMonth() + 1).padStart(2, '0')}`;
    }


    function render() {
        rangeEl.textContent = formatMonthLabel(current);

        const year = current.getFullYear();
        const month = current.getMonth();

        const first = new Date(year, month, 1);
        const last = new Date(year, month + 1, 0);

        const start = new Date(first);
        const day = (start.getDay() + 6) % 7;
        start.setDate(start.getDate() - day);

        const end = new Date(last);
        const endDay = (end.getDay() + 6) % 7;
        end.setDate(end.getDate() + (6 - endDay));

        const today = new Date();
        const todayKey = fmt(today);

        const days = [];
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            days.push(new Date(d));
        }

        const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const currentData = getCurrentEmployeeData();

        gridEl.innerHTML = `
            <div class="grid grid-cols-7 gap-px rounded-2xl overflow-hidden ring-1 ring-gray-200 bg-gray-200">
                ${dayNames.map((n, i) => `
                    <div class="bg-white px-4 py-3 text-xs font-semibold ${i === 6 ? 'text-rose-600' : 'text-gray-600'}">
                        ${n}
                    </div>
                `).join('')}

                ${days.map(d => {
                    const key = fmt(d);
                    const inMonth = d.getMonth() === month;
                    const isSun = ((d.getDay() + 6) % 7) === 6;
                    const cell = currentData[key];

                    const inT = cell?.in ?? '';
                    const outT = cell?.out ?? '';
                    const st = cell?.status ?? '';
                    const stClass = statusColor(st);

                    return `
                        <button
                            type="button"
                            data-date="${key}"
                            class="bg-white px-4 py-3 text-left min-h-[108px] hover:bg-gray-50 transition flex flex-col
                                   ${inMonth ? '' : 'opacity-40'}
                                   focus:outline-none"
                        >
                            <div class="flex items-start justify-between">
                                <div class="text-sm font-semibold">
                                    ${key === todayKey ? `
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#faa938] text-white">
                                            ${d.getDate()}
                                        </span>
                                    ` : `
                                        <span class="${isSun ? 'text-rose-600' : 'text-gray-900'}">
                                            ${d.getDate()}
                                        </span>
                                    `}
                                </div>
                            </div>

                            <div class="flex-1 flex items-center justify-center text-xs leading-5 text-gray-800">
                                ${pegawaiFilter.value
                                    ? cell
                                        ? `
                                            <div class="text-center">
                                                <div>${inT}</div>
                                                <div>${outT}</div>
                                                <div class="font-semibold ${stClass}">${st}</div>
                                            </div>
                                        `
                                        : `<div class="text-gray-300 select-none">—</div>`
                                    : `<div class="text-gray-300 select-none">—</div>`
                                }
                            </div>
                        </button>
                    `;
                }).join('')}
            </div>
        `;

        gridEl.querySelectorAll('button[data-date]').forEach(btn => {
            btn.addEventListener('click', () => {
                if (!pegawaiFilter.value) return;

                const date = btn.getAttribute('data-date');
                const cell = currentData[date];

                window.dispatchEvent(new CustomEvent('presensi-detail', {
                    detail: {
                        date,
                        status: cell?.status ?? '-',
                        checkIn: cell?.in ?? '-',
                        checkOut: cell?.out ?? '-',
                        note: cell?.note ?? 'Tidak ada data',
                        source: 'Upload Admin',
                    }
                }));
            });
        });

        updateSelectedInfo();
    }

    document.getElementById('btnToday').addEventListener('click', () => {
        const now = new Date();
        current = new Date(now.getFullYear(), now.getMonth(), 1);
        syncMonthInput();
        render();
    });

    document.getElementById('btnPrev').addEventListener('click', () => {
        current = new Date(current.getFullYear(), current.getMonth() - 1, 1);
        syncMonthInput();
        render();
    });

    document.getElementById('btnNext').addEventListener('click', () => {
        current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
        syncMonthInput();
        render();
    });

    pegawaiFilter.addEventListener('change', () => {
        render();
    });

    monthFilter.addEventListener('change', (e) => {
        if (!e.target.value) return;

        const [year, month] = e.target.value.split('-');
        current = new Date(Number(year), Number(month) - 1, 1);
        render();
    });

    populateEmployeeFilter();
    syncMonthInput();
    render();
});
</script>
@endpush
