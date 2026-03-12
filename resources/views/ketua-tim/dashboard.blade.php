@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<section class="mx-auto max-w-7xl py-8 px-4 sm:px-8 md:px-10 lg:px-20 ">
  <div
    class="relative flex w-full flex-col items-center bg-[#f9b800]
           rounded-[30px] px-10 py-10 lg:py-12 lg:flex-row
           overflow-visible min-h-55"
  >

    <!-- Text -->
    <div class="text-center md:text-left lg:flex-1 max-w-xl">
      <h2 id="greeting" class="mb-4 text-2xl lg:text-3xl font-semibold leading-tight">
        Selamat Datang, Ketua Tim
      </h2>

      <p class="text-[18px] leading-relaxed text-gray-900">
        Kelola pengajuan lembur dan pantau perkembangannya dengan lebih mudah.
      </p>
    </div>

    <!-- Image -->
    <img
      class="order-2 w-full max-w-130 object-contain
             lg:order-0 lg:absolute lg:right-0 lg:-top-16
             drop-shadow-xl"
      src="{{asset('images/2.svg')}}"
      alt=""
    />

  </div>
</section>

<section class="mx-auto max-w-7xl py-8 px-4 sm:px-8 md:px-10 lg:px-20 ">

    {{-- Main Content --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 mt-5">

        {{-- Left: Pengajuan Lembur --}}
        <div class="xl:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Pengajuan Lembur</h3>
                    <p class="text-sm text-slate-500">Daftar pengajuan lembur terbaru dari anggota tim</p>
                </div>

                <a href="{{ route('ketua-tim.pengajuan') }}"
                   class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold border border-black/15 hover:bg-gray-50">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-175 text-sm text-center">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-medium">Nama Pegawai</th>
                            <th class="px-6 py-4 font-medium">Tanggal</th>
                            <th class="px-6 py-4 font-medium">Jam</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">Pegawai 1</td>
                            <td class="px-6 py-4">11 Maret 2026</td>
                            <td class="px-6 py-4">18.00 - 21.00</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">
                                    Menunggu
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">Pegawai 2</td>
                            <td class="px-6 py-4">11 Maret 2026</td>
                            <td class="px-6 py-4">19.00 - 22.00</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600">
                                    Disetujui
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">Pegawai 3</td>
                            <td class="px-6 py-4">11 Maret 2026</td>
                            <td class="px-6 py-4">17.30 - 20.30</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-medium text-rose-600">
                                    Ditolak
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Pegawai Lembur Hari Ini --}}
        <div class="xl:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-5 py-5 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Lembur Hari Ini</h3>
                <p class="text-sm text-slate-500">Pegawai yang sedang / akan lembur hari ini</p>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-800">Pegawai 1</p>
                    </div>
                    <span class="text-sm text-slate-500">18.00 - 21.00</span>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-800">Pegawai 2</p>
                    </div>
                    <span class="text-sm text-slate-500">19.00 - 22.00</span>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
  function getGreeting(hour) {
    if (hour >= 4 && hour < 11) return "Selamat Pagi";
    if (hour >= 11 && hour < 15) return "Selamat Siang";
    if (hour >= 15 && hour < 18) return "Selamat Sore";
    return "Selamat Malam";
  }

  const now = new Date();
  const hour = now.getHours();
  const greeting = getGreeting(hour);

  const el = document.getElementById("greeting");
  const userName = "User";
  el.textContent = `${greeting}, ${userName}`;
</script>

@endsection
