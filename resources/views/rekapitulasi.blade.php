@extends('layouts.app')

@section('title', 'Rekapitulasi')

@section('content')
    <div class="w-9/10 mx-auto flex flex-col">
        <div class="overflow-x-auto">
            <div class="w-full inline-block align-middle">
                <!-- Period Picker (Month-Year) -->
                <div class="flex items-center justify-between mb-5 mt-5">
                    <div class="relative" id="periodPicker">
                        <!-- Trigger -->
                        <button
                            type="button"
                            id="periodBtn"
                            class="inline-flex items-center h-10 gap-2 px-4 text-sm font-semibold border border-[#faa938] bg-white text-gray-900 rounded-full"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                                <path
                                    d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"
                                />
                            </svg>

                            <span id="periodLabel" class="leading-none">Periode</span>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-80">
                                <path
                                    d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"
                                />
                            </svg>
                        </button>

                        <input type="hidden" id="periodValue" name="period" value="">

                        <!-- Popup -->
                        <div
                            id="periodPanel"
                            class="hidden absolute z-50 mt-2 w-80 rounded-xl border border-gray-200 bg-white shadow-lg p-3"
                        >
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-3">
                                <button
                                    type="button"
                                    id="yearPrev"
                                    class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                                        <path
                                            d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                                        />
                                    </svg>
                                </button>

                                <!-- Tahun (klik untuk edit inline) -->
                                <div class="w-20 text-center">
                                    <button
                                        type="button"
                                        id="yearDisplay"
                                        class="w-full text-sm font-semibold text-gray-900 px-2 py-1 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-200"
                                    >
                                        <span id="yearLabel">2026</span>
                                    </button>

                                    <input
                                        id="yearInput"
                                        type="number"
                                        min="1900"
                                        max="2100"
                                        class="hidden w-full text-center text-sm font-semibold bg-transparent border-b border-transparent focus:border-[#faa938] focus:outline-none"
                                    />
                                </div>

                                <button
                                    type="button"
                                    id="yearNext"
                                    class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                                        <path
                                            d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- Grid bulan -->
                            <div class="grid grid-cols-3 gap-2" id="monthGrid"></div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between mt-3">
                                <button type="button" id="btnThisMonth" class="text-sm font-medium text-gray-600 hover:text-[#faa938]">
                                    Bulan ini
                                </button>

                                <button
                                    type="button"
                                    id="btnClosePanel"
                                    class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:border-[#faa938] hover:text-[#faa938]"
                                >
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-visible">
                    <table class="min-w-full rounded-xl">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide">

                                {{-- Kolom tanpa tooltip --}}
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">
                                    Tanggal
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    Jenis Hari
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    Jam Disetujui
                                </th>

                                {{-- Kolom dengan tooltip --}}
                                @php
                                $tooltips = [
                                    'HB 2' => 'Hari Biasa, lembur 2 jam',
                                    'HB 3' => 'Hari Biasa, lembur 3 jam',
                                    'HB 4' => 'Hari Biasa, lembur 4 jam',
                                    'HL 2' => 'Hari Libur, lembur 2 jam',
                                    'HL 3' => 'Hari Libur, lembur 3 jam',
                                    'HL 4' => 'Hari Libur, lembur 4 jam',
                                    'HL 5' => 'Hari Libur, lembur 5 jam',
                                    'HL 6' => 'Hari Libur, lembur 6 jam',
                                ];
                                @endphp

                                @foreach($tooltips as $label => $desc)
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize relative group
                                                    {{ $loop->last ? 'rounded-tr-xl' : '' }}">
                                    {{ $label }}
                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max
                                                rounded-lg bg-gray-900 px-3 py-1.5 text-xs text-white
                                                opacity-0 group-hover:opacity-100 transition-opacity duration-200
                                                pointer-events-none z-10 normal-case font-normal tracking-normal whitespace-nowrap">
                                        {{ $desc }}
                                    </span>
                                </th>
                                @endforeach

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-300">
                            @forelse($rows as $r)
                            @php
                                $jam   = $r['jam'];
                                $isHb  = $r['hari'] == 0;
                                $isHl  = $r['hari'] == 1;
                                $centang = '<svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"/></svg>';
                            @endphp
                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($r['tanggal'])->translatedFormat('d F Y') }}
                                </td>
                                <td class="p-5 whitespace-nowrap text-sm font-medium text-gray-900">{{ $r['jenis_hari'] }}</td>
                                <td class="p-5 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $jam }}</td>

                                <td class="p-5 text-center">{!! ($isHb && $jam == 2) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHb && $jam == 3) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHb && $jam == 4) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHl && $jam == 2) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHl && $jam == 3) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHl && $jam == 4) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHl && $jam == 5) ? $centang : '' !!}</td>
                                <td class="p-5 text-center">{!! ($isHl && $jam == 6) ? $centang : '' !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="p-8 text-center text-sm text-gray-400">
                                    Tidak ada data lembur untuk periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="border-t border-gray-600 font-semibold">
                                <td colspan="3" class="p-5 text-right">Total</td>
                                <td class="p-5 text-center">{{ $total['hb2'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hb3'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hb4'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hl2'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hl3'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hl4'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hl5'] ?: '-' }}</td>
                                <td class="p-5 text-center">{{ $total['hl6'] ?: '-' }}</td>
                            </tr>
                            <tr class="border-t border-gray-600 font-semibold">
                                <td colspan="3" class="p-5 text-right">Jumlah Bekerja Hari Biasa</td>
                                <td class="p-5 text-center">{{ $jumlahHb ?: '-' }}</td>
                                <td colspan="6" class="p-5 text-right">Jumlah Bekerja Hari Libur</td>
                                <td class="p-5 text-center">{{ $jumlahHl ?: '-' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

    </div>

<script>
(function () {
  const el = (id) => document.getElementById(id);

  const periodPicker = el("periodPicker");
  const btn = el("periodBtn");
  const panel = el("periodPanel");

  const monthGrid = el("monthGrid");
  const yearLabel = el("yearLabel");
  const yearDisplay = el("yearDisplay");
  const yearInput = el("yearInput");

  const periodLabel = el("periodLabel");
  const periodValue = el("periodValue");

  const yearPrev = el("yearPrev");
  const yearNext = el("yearNext");
  const btnThisMonth = el("btnThisMonth");
  const btnClosePanel = el("btnClosePanel");

  if (!periodPicker || !btn || !panel) return;

  const monthNames = [
    "Januari","Februari","Maret","April","Mei","Juni",
    "Juli","Agustus","September","Oktober","November","Desember"
  ];
  const now = new Date();

  let selectedYear = {{ \Carbon\Carbon::parse($bulan . '-01')->year }};
  let selectedMonth = {{ \Carbon\Carbon::parse($bulan . '-01')->month }};
  let viewYear = selectedYear;

  function pad2(n) { return String(n).padStart(2, "0"); }

  function setPeriod(year, month) {
    selectedYear = year;
    selectedMonth = month;
    periodLabel.textContent = `${monthNames[month - 1]} ${year}`;
    periodValue.value = `${year}-${pad2(month)}`;
    window.location.href = `?bulan=${year}-${pad2(month)}`;
}

  function syncYearUI() {
    if (yearLabel) yearLabel.textContent = String(viewYear);
    if (yearInput) yearInput.value = String(viewYear);
  }

  function renderMonths() {
    syncYearUI();
    monthGrid.innerHTML = "";

    for (let m = 1; m <= 12; m++) {
      const isSelected = (m === selectedMonth && viewYear === selectedYear);

      const b = document.createElement("button");
      b.type = "button";
      b.className =
        "px-2 py-2 text-sm rounded-lg border transition " +
        (isSelected
          ? "bg-[#faa938] text-white border-[#faa938]"
          : "bg-white text-gray-800 border-gray-200 hover:border-[#faa938] hover:text-[#faa938]");

      b.textContent = monthNames[m - 1].slice(0, 3);

      b.addEventListener("click", (e) => {
        e.stopPropagation();
        setPeriod(viewYear, m);
        closePanel();
      });

      monthGrid.appendChild(b);
    }
  }

  function openPanel() {
    panel.classList.remove("hidden");
    viewYear = selectedYear;
    renderMonths();
  }

  function exitEditMode() {
    if (!yearInput || !yearDisplay) return;
    yearInput.classList.add("hidden");
    yearDisplay.classList.remove("hidden");
  }

  function closePanel() {
    panel.classList.add("hidden");
    exitEditMode();
  }

  function startYearEdit() {
    if (!yearInput || !yearDisplay) return;
    yearDisplay.classList.add("hidden");
    yearInput.classList.remove("hidden");
    yearInput.focus();
    yearInput.select();
  }

  function applyYearFromInput(commit) {
    if (!yearInput) return;

    if (commit) {
      const y = parseInt(yearInput.value, 10);
      if (!Number.isNaN(y)) {
        selectedYear = y;
        viewYear = y;
      } else {
        yearInput.value = String(viewYear);
      }
    } else {
      yearInput.value = String(viewYear);
    }

    exitEditMode();
    renderMonths();
  }

  // Trigger popup
  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    panel.classList.contains("hidden") ? openPanel() : closePanel();
  });

  // Klik tahun -> edit
  yearDisplay?.addEventListener("click", (e) => {
    e.stopPropagation();
    startYearEdit();
  });

  // Enter / Escape / blur pada input
  yearInput?.addEventListener("keydown", (e) => {
    if (e.key === "Enter") { e.preventDefault(); applyYearFromInput(true); }
    if (e.key === "Escape") { e.preventDefault(); applyYearFromInput(false); }
  });

  yearInput?.addEventListener("blur", () => applyYearFromInput(true));

  // Prev / Next year
  yearPrev?.addEventListener("click", (e) => {
    e.stopPropagation();
    viewYear -= 1;
    selectedYear = viewYear;
    renderMonths();
  });

  yearNext?.addEventListener("click", (e) => {
    e.stopPropagation();
    viewYear += 1;
    selectedYear = viewYear;
    renderMonths();
  });

  // Bulan ini
  btnThisMonth?.addEventListener("click", (e) => {
    e.stopPropagation();
    setPeriod(now.getFullYear(), now.getMonth() + 1);
    closePanel();
  });

  // Tutup
  btnClosePanel?.addEventListener("click", (e) => {
    e.stopPropagation();
    closePanel();
  });

  // Klik luar -> tutup
  document.addEventListener("click", (e) => {
    if (!periodPicker.contains(e.target)) closePanel();
  });

  // Init default realtime
    periodLabel.textContent = `${monthNames[selectedMonth - 1]} ${selectedYear}`;
    periodValue.value = `${selectedYear}-${pad2(selectedMonth)}`;
    renderMonths();
})();

document.addEventListener('DOMContentLoaded', () => {
    const menu = document.getElementById('downloadMenu');
    const btn = document.getElementById('downloadBtn');
    const panel = document.getElementById('downloadPanel');

    const periodInput = document.getElementById('periodValue');

    const linkSpkl = document.getElementById('downloadSpkl');
    const linkLembur = document.getElementById('downloadLembur');
    const hint = document.getElementById('downloadHint');

    // GANTI route ini sesuai Laravel kamu
    const baseSpkl = '/rekapitulasi/download/spkl';
    const baseLembur = '/rekapitulasi/download/lembur';

    function setLinks() {
        const period = (periodInput?.value || '').trim();

        if (!period) {
            linkSpkl.href = '#';
            linkLembur.href = '#';
            linkSpkl.classList.add('pointer-events-none', 'opacity-50');
            linkLembur.classList.add('pointer-events-none', 'opacity-50');
            hint.textContent = 'Pilih periode dulu';
            return;
        }

        const qs = new URLSearchParams({ period }).toString();
        linkSpkl.href = `${baseSpkl}?${qs}`;
        linkLembur.href = `${baseLembur}?${qs}`;

        linkSpkl.classList.remove('pointer-events-none', 'opacity-50');
        linkLembur.classList.remove('pointer-events-none', 'opacity-50');
        hint.textContent = `Periode: ${period}`;
    }

    function openPanel() {
        setLinks();
        panel.classList.remove('hidden');
    }

    function closePanel() {
        panel.classList.add('hidden');
    }

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        panel.classList.contains('hidden') ? openPanel() : closePanel();
    });

    // Klik di luar -> tutup
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target)) closePanel();
    });

    // Kalau period berubah (dari JS period picker kamu), panggil setLinks()
    // Cara paling gampang: observe perubahan value
    if (periodInput) {
        const obs = new MutationObserver(setLinks);
        obs.observe(periodInput, { attributes: true, attributeFilter: ['value'] });
        periodInput.addEventListener('change', setLinks);
        periodInput.addEventListener('input', setLinks);
    }

    // Init
    setLinks();
});
</script>

@endsection
