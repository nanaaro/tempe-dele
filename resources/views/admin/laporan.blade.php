@extends('layouts.app')

@section('title', 'Laporan Lembur')

@section('content')
<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10">
    <div class="mt-5 mb-5 flex w-full items-center gap-4">
        <!-- Datepicker -->
        <input
            type="date"
            class="h-10 w-48 rounded-full border border-gray-300 px-3 text-sm text-gray-700 shadow-sm outline-none"
        />

        <!-- Dropdown searchable -->
        <div class="relative flex-1">
            <input
                type="text"
                id="searchPegawai"
                placeholder="Cari nama pegawai..."
                onclick="toggleDropdown()"
                oninput="filterDropdown()"
                autocomplete="off"
                class="h-10 w-full rounded-full border border-gray-300 px-4 pr-10 text-sm text-gray-700 outline-none focus:ring-1 focus:ring-gray-400"
            />

            <!-- Icon dropdown -->
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 640 640"
                    class="h-4 w-4 text-gray-400"
                >
                    <path
                        fill="currentColor"
                        d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"
                    />
                </svg>
            </div>

            <!-- Dropdown menu -->
            <div
                id="dropdownPegawai"
                class="absolute z-10 mt-1 hidden max-h-48 w-full overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg"
            >
                <ul id="listPegawai">
                    <li
                        onclick="pilihPegawai('Pegawai 1')"
                        class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                    >
                        Pegawai 1
                    </li>
                    <li
                        onclick="pilihPegawai('Pegawai 2')"
                        class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                    >
                        Pegawai 2
                    </li>
                    <li
                        onclick="pilihPegawai('Pegawai 3')"
                        class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                    >
                        Pegawai 3
                    </li>
                </ul>
            </div>
        </div>

        <!-- Download menu -->
        <div class="relative shrink-0" id="downloadMenu">
            <button
                type="button"
                id="downloadBtn"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-[#faa938] bg-white px-4 text-sm font-medium text-[#faa938] shadow-sm hover:bg-[#fff7ed]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 640 640"
                    class="h-5 w-5 fill-current"
                >
                    <path
                        d="M352 96C352 78.3 337.7 64 320 64C302.3 64 288 78.3 288 96L288 306.7L246.6 265.3C234.1 252.8 213.8 252.8 201.3 265.3C188.8 277.8 188.8 298.1 201.3 310.6L297.3 406.6C309.8 419.1 330.1 419.1 342.6 406.6L438.6 310.6C451.1 298.1 451.1 277.8 438.6 265.3C426.1 252.8 405.8 252.8 393.3 265.3L352 306.7L352 96zM160 384C124.7 384 96 412.7 96 448L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 448C544 412.7 515.3 384 480 384L433.1 384L376.5 440.6C345.3 471.8 294.6 471.8 263.4 440.6L206.9 384L160 384zM464 440C477.3 440 488 450.7 488 464C488 477.3 477.3 488 464 488C450.7 488 440 477.3 440 464C440 450.7 450.7 440 464 440z"
                    />
                </svg>
            </button>

            <div
                id="downloadPanel"
                class="absolute right-0 z-50 mt-2 hidden w-80 rounded-2xl border border-gray-200 bg-white p-4 shadow-lg"
            >
                <p class="mb-3 text-sm font-semibold text-gray-900">Download Laporan</p>

                <!-- Pilih tipe download -->
                <div class="mb-4 grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        id="btnModeHarian"
                        class="rounded-full border border-[#faa938] bg-[#fff7ed] px-3 py-2 text-sm font-medium text-[#faa938]"
                    >
                        Harian
                    </button>

                    <button
                        type="button"
                        id="btnModeBulanan"
                        class="rounded-full border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-600 hover:border-[#faa938] hover:text-[#faa938]"
                    >
                        Bulanan
                    </button>
                </div>

                <!-- Panel harian -->
                <div id="panelHarian" class="space-y-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500">
                            Tanggal yang akan diunduh
                        </label>
                        <input
                            type="date"
                            id="downloadTanggalHarian"
                            class="h-10 w-full rounded-xl border border-gray-300 px-3 text-sm text-gray-700 outline-none focus:ring-1 focus:ring-gray-400"
                        />
                    </div>

                    <button
                        type="button"
                        id="downloadHarianBtn"
                        class="w-full rounded-full bg-[#faa938] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f59e0b]"
                    >
                        Download
                    </button>
                </div>

                <!-- Panel bulanan -->
                <div id="panelBulanan" class="hidden space-y-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500">
                            Bulan
                        </label>
                        <select
                            id="downloadBulan"
                            class="h-10 w-full rounded-xl border border-gray-300 px-3 text-sm text-gray-700 outline-none focus:ring-1 focus:ring-gray-400"
                        >
                            <option value="">Pilih bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500">
                            Tahun
                        </label>
                        <select
                            id="downloadTahun"
                            class="h-10 w-full rounded-xl border border-gray-300 px-3 text-sm text-gray-700 outline-none focus:ring-1 focus:ring-gray-400"
                        >
                            <option value="">Pilih tahun</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                        </select>
                    </div>

                    <button
                        type="button"
                        id="downloadBulananBtn"
                        class="w-full rounded-full bg-[#faa938] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f59e0b]"
                    >
                        Download
                    </button>
                </div>

                <div class="my-4 border-t border-gray-100"></div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
            <div class="overflow-hidden">
                <table class="min-w-full rounded-xl">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">Nama Pegawai</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">NIP</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Kode</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">Uraian Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 1</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">5</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Input data</td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 2</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">5</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Input data</td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 3</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">5</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Input data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /Tabel -->

    {{-- Pagination --}}
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center p-1 rounded bg-white space-x-2">
            <a class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]" href="#">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                </svg>
            </a>
            <p class="text-gray-500">Page 1 of 10</p>
            <a class="p-1 rounded border text-black bg-white hover:text-white hover:bg-[#faa938] hover:border-[#faa938]" href="#">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                </svg>
            </a>
        </nav>
    </div>
</div>


<script>
    // =========================
    // DROPDOWN PEGAWAI
    // =========================
    function toggleDropdownPegawai() {
        const dropdown = document.getElementById('dropdownPegawai');
        dropdown.classList.toggle('hidden');
    }

    function filterDropdownPegawai() {
        const input = document.getElementById('searchPegawai');
        const filter = input.value.toLowerCase();
        const ul = document.getElementById('listPegawai');
        const li = ul.getElementsByTagName('li');

        document.getElementById('dropdownPegawai').classList.remove('hidden');

        for (let i = 0; i < li.length; i++) {
            const textValue = li[i].textContent || li[i].innerText;
            li[i].style.display = textValue.toLowerCase().includes(filter) ? '' : 'none';
        }
    }

    function pilihPegawai(nama) {
        document.getElementById('searchPegawai').value = nama;
        document.getElementById('dropdownPegawai').classList.add('hidden');
    }

    // =========================
    // DOWNLOAD DROPDOWN
    // =========================
    const downloadBtn = document.getElementById('downloadBtn');
    const downloadPanel = document.getElementById('downloadPanel');

    const btnModeHarian = document.getElementById('btnModeHarian');
    const btnModeBulanan = document.getElementById('btnModeBulanan');

    const panelHarian = document.getElementById('panelHarian');
    const panelBulanan = document.getElementById('panelBulanan');

    const tanggalFilter = document.getElementById('tanggalFilter');
    const downloadTanggalHarian = document.getElementById('downloadTanggalHarian');

    const downloadBulan = document.getElementById('downloadBulan');
    const downloadTahun = document.getElementById('downloadTahun');

    const downloadHarianBtn = document.getElementById('downloadHarianBtn');
    const downloadBulananBtn = document.getElementById('downloadBulananBtn');

    downloadBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        downloadPanel.classList.toggle('hidden');

        // Sinkronkan tanggal harian dengan filter tanggal utama
        if (tanggalFilter.value) {
            downloadTanggalHarian.value = tanggalFilter.value;
        }
    });

    btnModeHarian.addEventListener('click', function () {
        panelHarian.classList.remove('hidden');
        panelBulanan.classList.add('hidden');

        btnModeHarian.className =
            'rounded-full border border-[#faa938] bg-[#fff7ed] px-3 py-2 text-sm font-medium text-[#faa938]';

        btnModeBulanan.className =
            'rounded-full border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-600 hover:border-[#faa938] hover:text-[#faa938]';
    });

    btnModeBulanan.addEventListener('click', function () {
        panelBulanan.classList.remove('hidden');
        panelHarian.classList.add('hidden');

        btnModeBulanan.className =
            'rounded-full border border-[#faa938] bg-[#fff7ed] px-3 py-2 text-sm font-medium text-[#faa938]';

        btnModeHarian.className =
            'rounded-full border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-600 hover:border-[#faa938] hover:text-[#faa938]';
    });

    // Saat tanggal utama berubah, update juga tanggal download harian
    tanggalFilter.addEventListener('change', function () {
        downloadTanggalHarian.value = this.value;
    });

    // Auto isi bulan + tahun dari filter tanggal utama
    tanggalFilter.addEventListener('change', function () {
        if (this.value) {
            const [year, month] = this.value.split('-');
            downloadBulan.value = month;
            downloadTahun.value = year;
        }
    });

    // Contoh aksi download harian
    downloadHarianBtn.addEventListener('click', function () {
        const tanggal = downloadTanggalHarian.value;

        if (!tanggal) {
            alert('Pilih tanggal terlebih dahulu untuk download laporan harian.');
            return;
        }

        // Ganti URL ini sesuai route Laravel kamu
        const url = `/laporan/download/harian?tanggal=${tanggal}`;
        window.location.href = url;
    });

    // Contoh aksi download bulanan
    downloadBulananBtn.addEventListener('click', function () {
        const bulan = downloadBulan.value;
        const tahun = downloadTahun.value;

        if (!bulan || !tahun) {
            alert('Pilih bulan dan tahun terlebih dahulu untuk download laporan bulanan.');
            return;
        }

        // Ganti URL ini sesuai route Laravel kamu
        const url = `/laporan/download/bulanan?bulan=${bulan}&tahun=${tahun}`;
        window.location.href = url;
    });

    // Klik di luar panel untuk menutup dropdown
    document.addEventListener('click', function (e) {
        const dropdownPegawai = document.getElementById('dropdownPegawai');
        const searchPegawai = document.getElementById('searchPegawai');
        const downloadMenu = document.getElementById('downloadMenu');

        if (!searchPegawai.contains(e.target) && !dropdownPegawai.contains(e.target)) {
            dropdownPegawai.classList.add('hidden');
        }

        if (!downloadMenu.contains(e.target)) {
            downloadPanel.classList.add('hidden');
        }
    });
</script>

@endsection
