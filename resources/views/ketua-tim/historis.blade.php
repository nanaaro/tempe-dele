@extends('layouts.app')

@section('title', 'Historis Pengajuan')

@section('content')

<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10">

    <div class="flex items-center gap-3 mb-5 mt-5 w-full">

        <!-- Datepicker -->
        <input type="date"
            class="border rounded-full px-3 py-2 text-sm outline-none border-gray-300 text-gray-700 focus:ring-1 focus:ring-gray-400 w-48" />

        <!-- Dropdown searchable -->
        <div class="relative flex-1">
            <input type="text" id="searchPegawai" placeholder="Cari nama pegawai..."
                onclick="toggleDropdown()"
                oninput="filterDropdown()"
                autocomplete="off"
                class="border rounded-full px-3 py-2 text-sm outline-none border-gray-300 text-gray-700 focus:ring-1 focus:ring-gray-400 w-full"
                 />

            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 640 640"
                    class="w-4 h-4 text-gray-400">
                    <path fill="currentColor"
                        d="M300.3 440.8C312.9 451 331.4 450.3 343.1 438.6L471.1 310.6C480.3 301.4 483 287.7 478 275.7C473 263.7 461.4 256 448.5 256L192.5 256C179.6 256 167.9 263.8 162.9 275.8C157.9 287.8 160.7 301.5 169.9 310.6L297.9 438.6L300.3 440.8z"/>
                </svg>
            </div>

            <div id="dropdownPegawai"
                class="hidden absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                <ul id="listPegawai">
                    <li onclick="pilihPegawai('Pegawai 1')"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Pegawai 1</li>
                    <li onclick="pilihPegawai('Pegawai 2')"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Pegawai 2</li>
                    <li onclick="pilihPegawai('Pegawai 3')"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Pegawai 3</li>
                </ul>
            </div>
        </div>

    </div>

    <div class="overflow-hidden ">
                <table class="min-w-full rounded-xl table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">Nama Pegawai</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">NIP</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Jam Diajukan</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Jam Disetujui</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Uraian Kegiatan</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Keputusan</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Pegawai 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">12345</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">Input data</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">Disetujui</td>
                            <td class="px-3 py-3 text-center"> </td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Pegawai 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">12345</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">Input data</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">Disetujui</td>
                            <td class="px-3 py-3 text-center"> </td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Pegawai 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">12345</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">Input data</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">Disetujui</td>
                            <td class="px-3 py-3 text-center"> </td>
                        </tr>
                    </tbody>
                </table>
            </div>

    {{-- Modal Presensi --}}
    <div id="modalPresensi" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">

                <!-- Header -->
                <div class="flex items-center justify-between border-b px-6 py-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Validasi Presensi</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Pegawai 1 · NIP 12345</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>


                <!-- Body -->
                <div class="px-6 py-5 space-y-5">

                    <!-- Info Presensi Read Only -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <div class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">Senin, 9 Maret 2026</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Kehadiran</label>
                        <div id="roStatus" class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">-</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Kedatangan</label>
                            <div id="roJamDatang" class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">-</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Kepulangan</label>
                            <div class="border rounded-lg px-4 py-2 text-sm text-gray-700 bg-gray-50 border-gray-200">17.00</div>
                        </div>
                    </div>

                    <!-- Keputusan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="setKeputusan('tolak')" id="btnTolak"
                                class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all">
                                Tolak Lembur
                            </button>
                            <button onclick="setKeputusan('setujui')" id="btnSetujui"
                                class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all">
                                Setujui Lembur
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 px-6 py-4">
                    <button onclick="closeModal()"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button onclick="simpan()"
                        class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white transition-all">
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Keputusan --}}
    <div id="modalKeputusan" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModalKeputusan()"></div>
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">

                <div class="flex items-center justify-between border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Keputusan Lembur</h2>
                    <button onclick="closeModalKeputusan()" class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
                </div>

                <div class="px-6 py-5 space-y-5">

                    <!-- Jam Disetujui -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai Disetujui</label>
                            <input id="kJamMulai" type="time" id="kJamMulai"
                                class="border rounded-lg px-3 py-2 text-sm w-full outline-none border-gray-300 focus:ring-1 focus:ring-gray-400" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai Disetujui</label>
                            <input id="kJamSelesai" type="time"
                                class="border rounded-lg px-3 py-2 text-sm w-full outline-none border-gray-300 focus:ring-1 focus:ring-gray-400" />
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="kCatatan" rows="3"
                            class="border rounded-lg px-3 py-2 text-sm w-full outline-none border-gray-300 focus:ring-1 focus:ring-gray-400"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <!-- Status Keputusan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="setKeputusanAkhir('tolak')" id="kBtnTolak"
                                class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all">
                                Tolak
                            </button>
                            <button onclick="setKeputusanAkhir('setujui')" id="kBtnSetujui"
                                class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all">
                                Setujui
                            </button>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 px-6 py-4">
                    <button onclick="closeModalKeputusan()" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button onclick="simpanKeputusan()" class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white transition-all">Simpan</button>
                </div>
            </div>
        </div>
    </div>

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

<script>

// Dropsown Pegawai
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

