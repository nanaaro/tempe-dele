@extends('layouts.app')

@section('title', 'Akumulasi')

@section('content')

    <div class="flex items-center gap-3 max-w-7xl mx-auto px-8 my-5">

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

    {{-- Spacer atau devider --}}
    <div class="flex-1"></div>

    {{-- tombol upload --}}
        <a href="{{ route('admin.akumulasi.download', array_merge(['bulan' => $bulan], request()->only('tim', 'pegawai'))) }}"
            title="Unduh Akumulasi"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
        </a>

    </div>

    <!-- Tabel -->
    <div class="overflow-hidden max-w-7xl mx-auto px-8 my-5">
                <table class="min-w-full rounded-xl">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">Tanggal</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Nama Pegawai</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">NIP</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Hari</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Jam diajukan</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Jam disetujui</th>
                            <th scope="col" class="p-3 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">Golongan</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Uang Lembur</th>
                            <th scope="col" class="p-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Uang Makan</th>
                            <th scope="col" class="p-3 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">Jumlah</th>
                            <th scope="col" class="p-3 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">PPh 21</th>
                            <th scope="col" class="p-3 text-left text-xs leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">Terima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @forelse($akumulasi as $a)
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900">
                                {{ \Carbon\Carbon::parse($a->date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900">{{ $a->nama }}</td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900">{{ $a->nip }}</td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900">
                                {{ $a->hari == 0 ? 'Bekerja' : 'Libur' }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900">{{ $a->jam_diajukan }} jam</td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900">{{ $a->jam_disetujui }} jam</td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900 text-center">{{ $a->golongan ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900 text-left">
                                {{ number_format($a->total_uang_lembur, 0, ',', '.') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900 text-left">
                                {{ number_format($a->total_uang_makan, 0, ',', '.') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900 text-left">
                                {{ number_format($a->total_jumlah, 0, ',', '.') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900 text-left">
                                {{ number_format($a->total_pajak, 0, ',', '.') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-xs text-gray-900 text-left">
                                {{ number_format($a->total_terima, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="p-8 text-center text-sm text-gray-400">
                                Tidak ada data akumulasi untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- /Tabel -->

        </div>
    </div>

<!-- Pagination -->
@if($akumulasi->hasPages())
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
            @if($akumulasi->onFirstPage())
                <span class="p-1 rounded border text-gray-300 cursor-not-allowed">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </span>
            @else
                <a href="{{ $akumulasi->previousPageUrl() }}" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </a>
            @endif
            <p class="text-gray-500">Page {{ $akumulasi->currentPage() }} of {{ $akumulasi->lastPage() }}</p>
            @if($akumulasi->hasMorePages())
                <a href="{{ $akumulasi->nextPageUrl() }}" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
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


<script>
const now = new Date();
let selectedEmployeeId = null;
let selectedTeamId = null;
let cachedTim = @json($tim);
let cachedPegawai = [];

function pad2(n) { return String(n).padStart(2, '0'); }
function makeBtn(text, cls, onClick) {
    const b = document.createElement('button');
    b.type = 'button'; b.textContent = text; b.className = cls;
    b.addEventListener('click', (e) => { e.stopPropagation(); onClick(); });
    return b;
}

function updateURL() {
    const params = new URLSearchParams();
    params.set('bulan', '{{ $bulan }}');
    if (selectedTeamId)     params.set('tim', selectedTeamId);
    if (selectedEmployeeId) params.set('pegawai', selectedEmployeeId);
    window.location.href = '?' + params.toString();
}

// DROPDOWN PEGAWAI
function populateDropdown(filter = '', data = []) {
    const list = document.getElementById('listPegawai');
    list.innerHTML = '';
    const liSemua = document.createElement('li');
    liSemua.className = 'cursor-pointer px-4 py-2 text-sm text-gray-400 hover:bg-gray-50';
    liSemua.textContent = 'Semua pegawai';
    liSemua.onclick = () => { selectedEmployeeId = null; document.getElementById('searchPegawai').value = ''; document.getElementById('dropdownPegawai').classList.add('hidden'); updateURL(); };
    list.appendChild(liSemua);
    data.filter(e => `${e.nama} ${e.nip}`.toLowerCase().includes(filter.toLowerCase()))
        .forEach(emp => {
            const li = document.createElement('li');
            li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
            li.textContent = `${emp.nama} - ${emp.nip}`;
            li.onclick = () => { selectedEmployeeId = emp.nip; document.getElementById('searchPegawai').value = `${emp.nama} - ${emp.nip}`; document.getElementById('dropdownPegawai').classList.add('hidden'); updateURL(); };
            list.appendChild(li);
        });
}
window.toggleDropdown = function() { document.getElementById('dropdownPegawai').classList.toggle('hidden'); populateDropdown('', cachedPegawai); };
window.filterDropdown = function() { populateDropdown(document.getElementById('searchPegawai').value, cachedPegawai); document.getElementById('dropdownPegawai').classList.remove('hidden'); };

// DROPDOWN TIM
function populateDropdownTim(filter = '', data = []) {
    const list = document.getElementById('listTim');
    list.innerHTML = '';
    data.filter(t => t.nama_tim.toLowerCase().includes(filter.toLowerCase()))
        .forEach(tim => {
            const li = document.createElement('li');
            li.className = 'cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
            li.textContent = tim.nama_tim;
            li.onclick = () => {
                selectedTeamId = tim.kode_tim;
                document.getElementById('searchTim').value = tim.nama_tim;
                document.getElementById('dropdownTim').classList.add('hidden');
                selectedEmployeeId = null;
                document.getElementById('searchPegawai').value = '';
                fetch(`/admin/presensi/pegawai?kode_tim=${tim.kode_tim}`)
                    .then(r => r.json()).then(d => { cachedPegawai = d; populateDropdown('', d); });
                updateURL();
            };
            list.appendChild(li);
        });
}
window.toggleDropdownTim = function() {
    document.getElementById('dropdownTim').classList.toggle('hidden');
    populateDropdownTim('', cachedTim);
};
window.filterDropdownTim = function() {
    populateDropdownTim(document.getElementById('searchTim').value, cachedTim);
    document.getElementById('dropdownTim').classList.remove('hidden');
};

// PERIOD PICKER
(function () {
    const el = (id) => document.getElementById(id);
    const picker = el('periodPicker'), btn = el('periodBtn'), panel = el('periodPanel');
    const grid = el('monthGrid'), navLabel = el('yearLabel');
    const periodLabel = el('periodLabel'), periodValue = el('periodValue');
    const btnPrev = el('yearPrev'), btnNext = el('yearNext');
    const btnThisMonth = el('btnThisMonth'), btnClose = el('btnClosePanel');

    if (!picker || !btn || !panel) return;

    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const monthShort = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    let viewYear = now.getFullYear();
    let selYear  = {{ \Carbon\Carbon::parse($bulan . '-01')->year }};
    let selMonth = {{ \Carbon\Carbon::parse($bulan . '-01')->month - 1 }};
    let view = 'month';

    function setPeriod(y, m) {
        selYear = y; selMonth = m;
        periodLabel.textContent = `${monthShort[m]} ${y}`;
        periodValue.value = `${y}-${pad2(m + 1)}`;
        window.location.href = `?bulan=${y}-${pad2(m + 1)}`;
    }

    function renderMonth() {
        view = 'month';
        navLabel.textContent = String(viewYear);
        navLabel.className = 'text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none';
        grid.innerHTML = '';
        // hapus: const g = document.createElement('div'); g.className = ...
        monthNames.forEach((name, m) => {
            const isSelected = (m === selMonth && viewYear === selYear);
            const isNow      = (m === now.getMonth() && viewYear === now.getFullYear());
            const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
            );
            grid.appendChild(makeBtn(name.slice(0, 3), cls, () => setPeriod(viewYear, m))); // ← langsung ke grid
        });
    }

    function renderYear() {
        view = 'year';
        const startYear = Math.floor(viewYear / 12) * 12;
        navLabel.textContent = `${startYear} - ${startYear + 11}`;
        navLabel.className = 'text-sm font-medium text-gray-400 select-none cursor-default';
        grid.innerHTML = '';
        // hapus: const g = document.createElement('div'); g.className = ...
        for (let y = startYear; y < startYear + 12; y++) {
            const isSelected = (y === selYear);
            const isNow = (y === now.getFullYear());
            const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                isSelected ? 'bg-[#faa938] text-white border-[#faa938]'
                : isNow    ? 'border-[#faa938] text-[#faa938] bg-white'
                :            'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
            );
            grid.appendChild(makeBtn(String(y), cls, () => { viewYear = y; renderMonth(); })); // ← langsung ke grid
        }
    }

    function openPanel()  { viewYear = selYear; renderMonth(); panel.classList.remove('hidden'); }
    function closePanel() { panel.classList.add('hidden'); }

    navLabel?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (view === 'month') renderYear();
        else if (view === 'year') renderMonth();
    });

    btn.addEventListener('click', (e) => { e.stopPropagation(); panel.classList.contains('hidden') ? openPanel() : closePanel(); });
    btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); viewYear--; renderMonth(); });
    btnNext?.addEventListener('click', (e) => { e.stopPropagation(); viewYear++; renderMonth(); });
    btnThisMonth?.addEventListener('click', (e) => { e.stopPropagation(); setPeriod(now.getFullYear(), now.getMonth()); });
    btnClose?.addEventListener('click', (e) => { e.stopPropagation(); closePanel(); });
    document.addEventListener('click', (e) => { if (!picker.contains(e.target)) closePanel(); });

    periodLabel.textContent = `${monthShort[selMonth]} ${selYear}`;
    renderMonth();
})();

// INIT
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
</script>

@endsection
