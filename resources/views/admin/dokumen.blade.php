@extends('layouts.app')
@section('title', 'Dokumen')
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

    {{-- Spacer --}}
    <div class="flex-1"></div>

    {{-- Tombol Download + Panel --}}
    <div class="relative shrink-0" id="downloadPicker">
        <button type="button" id="downloadBtn" title="Download Laporan"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faa938] text-white hover:brightness-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
        </button>
        <div id="downloadPanel" class="hidden absolute right-0 z-50 mt-2 w-72 rounded-2xl border border-gray-200 bg-white p-4 shadow-lg">
            <p class="mb-3 text-sm font-semibold text-gray-900">Download Laporan</p>

            {{-- Toggle mode --}}
            <div class="mb-4 grid grid-cols-2 gap-2">
                <button type="button" id="btnModeHarian"
                    class="rounded-full border border-[#faa938] bg-[#fff7ed] px-3 py-2 text-sm font-medium text-[#faa938]">
                    Harian
                </button>
                <button type="button" id="btnModeBulanan"
                    class="rounded-full border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-600 hover:border-[#faa938] hover:text-[#faa938]">
                    Bulanan
                </button>
            </div>

            {{-- Label hasil pilih --}}
            <p class="mb-2 text-xs text-gray-500">
                Periode: <span id="downloadLabel" class="font-medium text-gray-800">—</span>
            </p>

            {{-- Grid picker (diisi JS) --}}
            <div id="downloadPickerHeader" class="flex items-center justify-between mb-3">
                <button type="button" id="downloadPrev" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                    </svg>
                </button>
                <span id="downloadNavLabel" class="text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none"></span>
                <button type="button" id="downloadNext" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                        <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                    </svg>
                </button>
            </div>
            <div id="downloadGrid"></div>

            <div class="border-t border-gray-100 my-3"></div>

            <button type="button" id="downloadBtn2"
                class="w-full rounded-full bg-[#faa938] px-4 py-2 text-sm font-semibold text-white hover:brightness-95">
                Download
            </button>
        </div>
    </div>

</div>

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
                    $spkl      = $dokumenList->where('type', 'spkl')->where('periode', $periode)->first();
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
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-500 ml-1 text-base leading-none">×</button>
                                </form>
                            </div>
                        @else
                            {{-- Trigger modal, bukan langsung generate --}}
                            <button onclick="openModalNomor('{{ $periode }}')" title="Generate SPKL">
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
                                    <i class="fa-regular fa-file-pdf text-black text-bs"></i>
                                    Laporan_PNS_{{ $laporanPns->periode }}.pdf
                                </a>
                                <form method="POST" action="{{ route('admin.dokumen.hapus-satu', $laporanPns->id_dokumen) }}"
                                    onsubmit="return confirm('Hapus Laporan PNS periode ini?')">
                                    @csrf @method('DELETE')
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
                                    <i class="fa-regular fa-file-pdf text-black text-bs"></i>
                                    Laporan_PPPK_{{ $laporanPppk->periode }}.pdf
                                </a>
                                <form method="POST" action="{{ route('admin.dokumen.hapus-satu', $laporanPppk->id_dokumen) }}"
                                    onsubmit="return confirm('Hapus Laporan PPPK periode ini?')">
                                    @csrf @method('DELETE')
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

        {{-- Modal Nomor Surat --}}
        <div id="modalNomor" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/40" onclick="closeModalNomor()"></div>
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Generate SPKL</h2>
                        <button onclick="closeModalNomor()" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                    </div>
                    <form id="formGenerateSpkl" method="GET" class="px-6 py-5 space-y-4">
                        <input type="hidden" name="bulan" id="inputBulanSpkl">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat</label>
                            <input type="text" name="nomor_surat" required
                                placeholder="558.1/00001/RT.512/2026"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                            <p class="text-xs text-gray-400 mt-1">Contoh: 558.1/00001/RT.512/2026</p>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" onclick="closeModalNomor()"
                                class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white">
                                Generate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    // =====================
    // Modal Nomor Surat
    // =====================
    function openModalNomor(periode) {
        document.getElementById('inputBulanSpkl').value = periode;
        document.getElementById('formGenerateSpkl').action = '{{ route("admin.dokumen.generate.spkl") }}';
        document.getElementById('modalNomor').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeModalNomor() {
        document.getElementById('modalNomor').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // =====================
    // GLOBAL STATE
    // =====================
    const now = new Date();
    let current = new Date(now.getFullYear(), now.getMonth(), 1);
    let selectedEmployeeId = null;
    let selectedTeamId = null;
    let cachedPegawai = [];
    const gridEl = document.getElementById('presensiGrid');

    function pad2(n) { return String(n).padStart(2, '0'); }

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

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const monthShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        let view = 'month';

        // ambil nilai awal dari backend
        let selYear = {{ \Carbon\Carbon::parse($bulan . '-01')->year }};
        let selMonth = {{ \Carbon\Carbon::parse($bulan . '-01')->month - 1 }};
        let viewYear = selYear;

        function updateDisplayOnly(y, m) {
            periodLabel.textContent = `${monthShort[m]} ${y}`;
            periodValue.value = `${y}-${pad2(m + 1)}`;
        }

        function setPeriod(y, m) {
            selYear = y;
            selMonth = m;
            updateDisplayOnly(y, m);

            window.location.href = `?bulan=${y}-${pad2(m + 1)}`;
        }

        function renderMonth() {
            view = 'month';
            navLabel.textContent = String(viewYear);
            navLabel.className = 'text-sm font-medium text-gray-900 cursor-pointer hover:text-[#faa938] select-none';
            grid.innerHTML = '';

            monthNames.forEach((name, m) => {
                const isSelected = (m === selMonth && viewYear === selYear);
                const isNow = (m === now.getMonth() && viewYear === now.getFullYear());

                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected
                        ? 'bg-[#faa938] text-white border-[#faa938]'
                        : isNow
                            ? 'border-[#faa938] text-[#faa938] bg-white'
                            : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );

                grid.appendChild(
                    makeBtn(name.slice(0, 3), cls, () => {
                        setPeriod(viewYear, m);
                        closePanel();
                    })
                );
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
                const isNow = (y === now.getFullYear());

                const cls = 'px-2 py-2 text-sm rounded-lg border transition ' + (
                    isSelected
                        ? 'bg-[#faa938] text-white border-[#faa938]'
                        : isNow
                            ? 'border-[#faa938] text-[#faa938] bg-white'
                            : 'border-gray-200 text-gray-800 hover:border-[#faa938] hover:text-[#faa938]'
                );

                grid.appendChild(
                    makeBtn(String(y), cls, () => {
                        viewYear = y;
                        renderMonth();
                    })
                );
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

        function openPanel() {
            viewYear = selYear;
            renderMonth();
            panel.classList.remove('hidden');
        }

        function closePanel() {
            panel.classList.add('hidden');
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (panel.classList.contains('hidden')) {
                openPanel();
            } else {
                closePanel();
            }
        });

        navLabel.addEventListener('click', (e) => {
            e.stopPropagation();
            if (view === 'month') {
                renderYear();
            }
        });

        btnPrev?.addEventListener('click', (e) => {
            e.stopPropagation();
            navigate(-1);
        });

        btnNext?.addEventListener('click', (e) => {
            e.stopPropagation();
            navigate(1);
        });

        btnThisMonth?.addEventListener('click', (e) => {
            e.stopPropagation();
            setPeriod(now.getFullYear(), now.getMonth());
            closePanel();
        });

        btnClose?.addEventListener('click', (e) => {
            e.stopPropagation();
            closePanel();
        });

        document.addEventListener('click', (e) => {
            if (!picker.contains(e.target)) {
                closePanel();
            }
        });

        // tampilkan label awal tanpa redirect
        updateDisplayOnly(selYear, selMonth);
    })();

</script>
@endsection
