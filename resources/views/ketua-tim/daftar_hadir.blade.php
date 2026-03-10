@extends('layouts.app')

@section('title', 'Daftar Hadir')

@section('content')

<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10"">
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
                class="inline-flex h-10 items-center justify-center rounded-full border border-[#faa938] bg-white px-4 text-[#faa938] shadow-sm hover:bg-[#fff7ed]"
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
                class="absolute right-0 z-50 mt-2 hidden w-56 rounded-xl border border-gray-200 bg-white p-2 shadow-lg"
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

    <!-- Tabel -->
            <div class="overflow-hidden">
                <table class="min-w-full rounded-xl">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">Nama Pegawai</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">NIP</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Jam Datang</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Jam Pulang</th>
                            <th scope="col" class="p-5 text-center text-sm leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 1</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">10.37</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">18.20</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900"> </td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 2</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">11.10</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">14.00</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900"> </td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50 text-center">
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">Pegawai 3</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">12345</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">08.04</td>
                            <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">11.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /Tabel -->
</div>

@endsection
