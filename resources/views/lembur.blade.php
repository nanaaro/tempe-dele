@extends('layouts.app')

@section('title', 'Lembur')

@section('content')

<div class="w-full mx-auto flex flex-col sm:px-8 md:px-10 lg:px-10">
      <div class=" overflow-x-auto">
        <div class="w-full inline-block align-middle">
            <div class="flex justify-start mb-5 mt-5">
                <a  href="javascript:void(0)" id="btnAjukan"
                    class="inline-flex items-center h-10 gap-2 px-4 text-sm font-semibold border border-[#faa938] bg-white text-gray-900 rounded-full">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 640"
                        class="w-4 h-4 fill-current">
                    <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/>
                    </svg> Ajukan
                </a>
            </div>
            <div class="overflow-hidden ">
                <table class="min-w-full rounded-xl table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize rounded-tl-xl">Tanggal</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Jam Diajukan</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Jam Disetujui</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Uraian Kegiatan</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Ketua Tim</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Nama Tim</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Status</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize">Dokumentasi</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs leading-6 font-semibold text-gray-900 capitalize rounded-tr-xl">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">24 Februari 2026</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 max-w-35">Customer</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">User 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">Tim 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                <span class="bg-amber-100 rounded-full px-3 text-xs text-amber-700 py-0.5">Diproses</span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center"> </td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">-</td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">18 Februari 2026</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 max-w-35">Customer</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">User 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">Tim 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                <span class="bg-red-100 rounded-full px-3 text-xs text-red-600 py-0.5">Ditolak</span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                <span class="cursor-not-allowed">-</span>
                            </td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">-</td>
                        </tr>
                        <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">10 Februari 2026</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">16.00 - 18.00</td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 max-w-35">Customer</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">User 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">Tim 1</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                <span class="bg-green-100 rounded-full px-3 text-xs text-green-700 py-0.5">Disetujui</span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                <label class="cursor-pointer">
                                    <span id="doc-1" class="text-xs text-gray-400">Pilih file</span>
                                    <input type="file" class="hidden" onchange="handleFile(this, 'doc-1')">
                                </label>
                            </td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-900 text-center">-</td>
                        </tr>
                    </tbody>
                </table>
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

</div>

      <!-- MODAL -->
<div id="modalAjukan" class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="modalOverlay" class="absolute inset-0 bg-black/40"></div>

    <!-- Modal Container -->
    <div class="relative flex min-h-screen items-center justify-center p-4">

        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">

            <!-- Header -->
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    Ajukan Lembur
                </h2>
                <button id="btnCloseModal"
                        class="text-gray-500 hover:text-gray-700 text-xl leading-none">
                    &times;
                </button>
            </div>

            <!-- Body -->
            <form class="px-6 py-5 space-y-5">

                {{-- Ketua Tim --}}
                <div>
                    <label for="approver_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Ketua Tim
                    </label>

                    <select
                        id="approver_id"
                        name="approver_id"
                        required
                        class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm
                            focus:outline-none focus:ring-1 focus:ring-gray-900">
                        <option value="" selected>Pilih Ketua Tim</option>
                        <option value="1">User 1 (Ketua Tim)</option>
                        <option value="3">User 3 (Ketua Tim)</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input type="date"
                        class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                </div>

                <!-- Jam -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Mulai
                        </label>
                        <input type="time"
                            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Selesai
                        </label>
                        <input type="time"
                            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                    </div>
                </div>

                <!-- Uraian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Uraian Kegiatan
                    </label>
                    <textarea rows="3"
                        class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900"
                        placeholder="Contoh: Penyusunan laporan bulanan..."></textarea>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button"
                            id="btnCancel"
                            class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-black bg-[#faa938] rounded-lg hover:bg-[#fd9a10] hover:text-white">
                        Kirim
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
  const modal = document.getElementById('modalAjukan');
  const btnAjukan = document.getElementById('btnAjukan');
  const btnClose = document.getElementById('btnCloseModal');
  const btnCancel = document.getElementById('btnCancel');
  const overlay = document.getElementById('modalOverlay');

  function openModal() {
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function closeModal() {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  btnAjukan.addEventListener('click', openModal);
  btnClose.addEventListener('click', closeModal);
  btnCancel.addEventListener('click', closeModal);
  overlay.addEventListener('click', closeModal);

  function handleFile(input, labelId) {
    const label = document.getElementById(labelId);
    label.textContent = input.files[0].name;
}
</script>
@endpush
@endsection
