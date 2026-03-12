@extends('layouts.app')

@section('title', 'SPKL')

@section('content')

<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10"">
    <div class="mt-5 mb-5 flex w-full items-center gap-4">

        <!-- Periode -->
        <div class="relative shrink-0" id="periodPicker">
            <button type="button" id="periodBtn"
                class="inline-flex items-center h-10 gap-2 px-3 text-sm font-semibold border border-gray-300 bg-white text-gray-900 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-current">
                    <path d="M208 64c17.7 0 32 14.3 32 32v32h160V96c0-17.7 14.3-32 32-32s32 14.3 32 32v32h32c35.3 0 64 28.7 64 64v320c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64h32V96c0-17.7 14.3-32 32-32zm336 160H96v288c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32V224z"/>
                </svg>
                <span id="periodLabel" class="leading-none">Periode</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current opacity-80">
                    <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L160 301.5l119.1-119.1c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-136 136c-9.4 9.4-24.6 9.4-34 0z"/>
                </svg>
            </button>

            <input type="hidden" id="periodValue" name="period" value="">

            <div id="periodPanel" class="hidden absolute z-50 mt-2 w-80 rounded-xl border border-gray-200 bg-white shadow-lg p-3">
                <div class="flex items-center justify-between mb-3">
                    <button type="button" id="yearPrev" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                            <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                        </svg>
                    </button>
                    <div class="w-20 text-center">
                        <button type="button" id="yearDisplay" class="w-full text-sm font-semibold text-gray-900 px-2 py-1 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-200">
                            <span id="yearLabel">2026</span>
                        </button>
                        <input id="yearInput" type="number" min="1900" max="2100"
                            class="hidden w-full text-center text-sm font-semibold bg-transparent border-b border-transparent focus:border-[#faa938] focus:outline-none"/>
                    </div>
                    <button type="button" id="yearNext" class="p-2 rounded-lg border border-gray-200 hover:border-[#faa938] hover:text-[#faa938]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 fill-current">
                            <path d="M278.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c12.5 12.5 12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2" id="monthGrid"></div>
                <div class="flex items-center justify-between mt-3">
                    <button type="button" id="btnThisMonth" class="text-sm font-medium text-gray-600 hover:text-[#faa938]">Bulan ini</button>
                    <button type="button" id="btnClosePanel" class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:border-[#faa938] hover:text-[#faa938]">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Cari Pegawai -->
        <div class="relative flex-1">
            <input type="text" id="searchPegawai" placeholder="Cari nama pegawai..."
                onclick="toggleDropdown()" oninput="filterDropdown()" autocomplete="off"
                class="h-10 w-full rounded-full border border-gray-300 px-4 pr-10 text-sm text-gray-700 outline-none focus:ring-1 focus:ring-gray-400"/>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-4 w-4 text-gray-400">
                    <path fill="currentColor" d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/>
                </svg>
            </div>
            <div id="dropdownPegawai" class="hidden absolute z-10 mt-1 w-full max-h-48 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg">
                <ul id="listPegawai">
                    <li onclick="pilihPegawai('Pegawai 1')" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pegawai 1</li>
                    <li onclick="pilihPegawai('Pegawai 2')" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pegawai 2</li>
                    <li onclick="pilihPegawai('Pegawai 3')" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pegawai 3</li>
                </ul>
            </div>
        </div>

        <!-- Download -->
        <div class="relative shrink-0" id="downloadMenu">
            <button type="button" id="downloadBtn"
                class="inline-flex h-10 items-center justify-center rounded-full border border-[#faa938] bg-white px-4 text-[#faa938] shadow-sm hover:bg-[#fff7ed]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-5 w-5 fill-current">
                    <path d="M352 96C352 78.3 337.7 64 320 64C302.3 64 288 78.3 288 96L288 306.7L246.6 265.3C234.1 252.8 213.8 252.8 201.3 265.3C188.8 277.8 188.8 298.1 201.3 310.6L297.3 406.6C309.8 419.1 330.1 419.1 342.6 406.6L438.6 310.6C451.1 298.1 451.1 277.8 438.6 265.3C426.1 252.8 405.8 252.8 393.3 265.3L352 306.7L352 96zM160 384C124.7 384 96 412.7 96 448L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 448C544 412.7 515.3 384 480 384L433.1 384L376.5 440.6C345.3 471.8 294.6 471.8 263.4 440.6L206.9 384L160 384zM464 440C477.3 440 488 450.7 488 464C488 477.3 477.3 488 464 488C450.7 488 440 477.3 440 464C440 450.7 450.7 440 464 440z"/>
                </svg>
            </button>
            <div id="downloadPanel" class="hidden absolute right-0 z-50 mt-2 w-56 rounded-xl border border-gray-200 bg-white p-2 shadow-lg">
                <a id="downloadSpkl" href="#" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-50">
                    <span class="font-medium">SPKL</span>
                    <span class="ml-auto text-xs text-gray-400">PDF</span>
                </a>
                <a id="downloadLembur" href="#" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-50">
                    <span class="font-medium">Laporan Lembur</span>
                    <span class="ml-auto text-xs text-gray-400">PDF</span>
                </a>
                <div class="my-2 border-t border-gray-100"></div>
                <p id="downloadHint" class="px-3 pb-1 text-xs text-gray-500">Pilih periode dulu</p>
            </div>
        </div>

    </div>

<div class="overflow-hidden">
                    <table class="min-w-full rounded-xl">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">
                                    Nama
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    NIP
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HB 2
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HB 3
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HB 4
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HL 2
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HL 3
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HL 4
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HL 5
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    HL 6
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    Jumlah HB
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    Jam HL
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">
                                    Tanggal
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-300">
                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 1</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">6</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">1</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">7</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">5</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">43</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">30</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">1,2,3</td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 2</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">2</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">1</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">1</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">7</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">4</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">2,3,28</td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 3</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">6</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">2</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">3</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">0</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">3</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">30</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">18</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">3,7,12</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

<!-- Pagination -->
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
            <a href="#" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </a>
            <p class="text-gray-500">Page 1 of 10</p>
            <a href="#" class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </a>
        </nav>
    </div>
    <!-- /Pagination -->

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
  let selectedYear = now.getFullYear();
  let selectedMonth = now.getMonth() + 1; // 1-12
  let viewYear = selectedYear;

  function pad2(n) { return String(n).padStart(2, "0"); }

  function setPeriod(year, month) {
    selectedYear = year;
    selectedMonth = month;

    periodLabel.textContent = `${monthNames[month - 1]} ${year}`;
    periodValue.value = `${year}-${pad2(month)}`;

    // TODO: filter/fetch data based on periodValue.value
    // console.log("period selected:", periodValue.value);
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
  setPeriod(selectedYear, selectedMonth);
})();

function toggleDropdown() {
    document.getElementById('dropdownPegawai').classList.toggle('hidden');
}

function filterDropdown() {
    const search = document.getElementById('searchPegawai').value.toLowerCase();
    document.querySelectorAll('#listPegawai li').forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
}

function pilihPegawai(nama) {
    document.getElementById('pegawaiLabel').textContent = nama;
    document.getElementById('dropdownPegawai').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    if (!document.getElementById('pegawaiPicker').contains(e.target)) {
        document.getElementById('dropdownPegawai').classList.add('hidden');
    }
});
</script>

@endsection
