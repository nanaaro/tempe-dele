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

                    <div class="relative" id="downloadMenu">
                        <button
                            type="button"
                            id="downloadBtn"
                            class="inline-flex items-center h-10 gap-2 px-4 text-sm font-semibold
                               rounded-full border border-[#faa938] bg-[#faa938] text-white shadow-sm
                                hover:bg-[#f59e0b]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 fill-current">
                                <path d="M352 96C352 78.3 337.7 64 320 64C302.3 64 288 78.3 288 96L288 306.7L246.6 265.3C234.1 252.8 213.8 252.8 201.3 265.3C188.8 277.8 188.8 298.1 201.3 310.6L297.3 406.6C309.8 419.1 330.1 419.1 342.6 406.6L438.6 310.6C451.1 298.1 451.1 277.8 438.6 265.3C426.1 252.8 405.8 252.8 393.3 265.3L352 306.7L352 96zM160 384C124.7 384 96 412.7 96 448L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 448C544 412.7 515.3 384 480 384L433.1 384L376.5 440.6C345.3 471.8 294.6 471.8 263.4 440.6L206.9 384L160 384zM464 440C477.3 440 488 450.7 488 464C488 477.3 477.3 488 464 488C450.7 488 440 477.3 440 464C440 450.7 450.7 440 464 440z"/>
                            </svg>
                        </button>

                        <div
                            id="downloadPanel"
                            class="hidden absolute right-0 z-50 mt-2 w-56 rounded-xl border border-gray-200 bg-white shadow-lg p-2"
                        >
                            <a
                                id="downloadSpkl"
                                href="#"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-50"
                            >
                                <span class="font-medium">SPKL</span>
                                <span class="ml-auto text-xs text-gray-400">PDF</span>
                            </a>

                            <a
                                id="downloadLembur"
                                href="#"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-50"
                            >
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

                <div class="overflow-hidden">
                    <table class="min-w-full rounded-xl">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">
                                    Tanggal
                                </th>
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    Jenis Hari
                                </th>
                                <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">
                                    Jam Disetujui
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
                                <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">
                                    HL 6
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-300">
                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">2 Februari 2026</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Bekerja</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">2</td>

                                <td class="p-5 text-center">
                                    <svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path
                                            d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"
                                        />
                                    </svg>
                                </td>

                                <td colspan="7"></td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">5 Februari 2026</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Bekerja</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">3</td>

                                <td></td>

                                <td class="text-center">
                                    <svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path
                                            d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"
                                        />
                                    </svg>
                                </td>

                                <td colspan="6"></td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">8 Februari 2026</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Libur</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">6</td>

                                <td colspan="7"></td>

                                <td class="text-center">
                                    <svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path
                                            d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"
                                        />
                                    </svg>
                                </td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">11 Februari 2026</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Bekerja</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">2</td>

                                <td class="p-5 text-center">
                                    <svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path
                                            d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"
                                        />
                                    </svg>
                                </td>

                                <td colspan="7"></td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12 Februari 2026</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Bekerja</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">3</td>

                                <td></td>

                                <td class="text-center">
                                    <svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path
                                            d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"
                                        />
                                    </svg>
                                </td>

                                <td colspan="6"></td>
                            </tr>

                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">14 Februari 2026</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Libur</td>
                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900 text-center">6</td>

                                <td colspan="7"></td>

                                <td class="text-center">
                                    <svg class="w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path
                                            d="M530.8 134.1C545.1 144.5 548.3 164.5 537.9 178.8L281.9 530.8C276.4 538.4 267.9 543.1 258.5 543.9C249.1 544.7 240 541.2 233.4 534.6L105.4 406.6C92.9 394.1 92.9 373.8 105.4 361.3C117.9 348.8 138.2 348.8 150.7 361.3L252.2 462.8L486.2 141.1C496.6 126.8 516.6 123.6 530.9 134z"
                                        />
                                    </svg>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr class="border-t border-gray-600 font-semibold">
                                <td colspan="3" class="p-5 text-right">Total</td>
                                <td class="p-5 text-center">2</td>
                                <td class="p-5 text-center">2</td>
                                <td colspan="5"></td>
                                <td class="p-5 text-center">2</td>
                            </tr>

                            <tr class="border-t border-gray-600 font-semibold">
                                <td colspan="3" class="p-5 text-right">Jumlah Bekerja Hari Biasa</td>
                                <td class="p-5 text-center">10</td>
                                <td colspan="6" class="p-5 text-right">Jumlah Bekerja Hari Libur</td>
                                <td class="p-5 text-center">12</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-6">
            <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
                <a
                    class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]"
                    href="#"
                >
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            fill-rule="evenodd"
                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"
                        />
                    </svg>
                </a>

                <p class="text-gray-500">Page 1 of 10</p>

                <a
                    class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]"
                    href="#"
                >
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"
                        />
                    </svg>
                </a>
            </nav>
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
