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
        Selamat Datang, {{ session('user')['nama'] }}
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

<section class="w-full max-w-7xl px-4 sm:px-6 md:px-8 lg:px-20">
  <!-- Header -->
  <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Aktivitas</h1>
      <p class="mt-1 text-sm text-gray-600">
        Ringkasan aktivitas lembur dan dokumen terbaru.
      </p>
    </div>
  </div>

  <!-- Main grid: 75% + 25% -->
  <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
    <!-- Left: Latest submissions (75%) -->
    <div class="lg:col-span-9">
      <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-black/10 p-5 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h2 class="text-lg font-semibold">Pengajuan Terbaru</h2>
            <p class="mt-1 text-sm text-gray-600">Preview status pengajuan terakhir.</p>
          </div>

          <div class="flex gap-2">
            <a href="/lembur"
               class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold border border-black/15 hover:bg-gray-50">
              Lihat semua
            </a>
          </div>
        </div>

        <!-- List -->
        <div class="divide-y divide-black/5">
          <!-- Row -->
          <a href="/lembur"
             class="block p-5 hover:bg-gray-50">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <div class="min-w-0">
                <p class="text-sm font-semibold">
                  Sel, 4 Mar 2026
                </p>
                <p class="mt-1 text-sm text-gray-600">
                  18:00 - 21:30
                </p>
              </div>

              <div class="flex items-center gap-3">
                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                  Diproses
                </span>
                <span class="text-sm text-gray-400">›</span>
              </div>
            </div>
          </a>

          <a href="/lembur"
             class="block p-5 hover:bg-gray-50">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <div class="min-w-0">
                <p class="text-sm font-semibold">
                  Sen, 3 Mar 2026
                </p>
                <p class="mt-1 text-sm text-gray-600">
                  19:00 - 22:00
                </p>
              </div>

              <div class="flex items-center gap-3">
                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                  Disetujui
                </span>
                <span class="text-sm text-gray-400">›</span>
              </div>
            </div>
          </a>

          <a href="/lembur"
             class="block p-5 hover:bg-gray-50">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <div class="min-w-0">
                <p class="text-sm font-semibold">
                  Jum, 28 Feb 2026
                </p>
                <p class="mt-1 text-sm text-gray-600">
                  18:30 - 20:00
                </p>
              </div>

              <div class="flex items-center gap-3">
                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                  Ditolak
                </span>
                <span class="text-sm text-gray-400">›</span>
              </div>
            </div>
          </a>

        </div>
      </div>
    </div>

    <!-- Right: Documents / Notifications (25%) -->
    <div class="lg:col-span-3">
      <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="flex items-start justify-between border-b border-black/10 p-5">
          <div>
            <h2 class="text-lg font-semibold">Dokumen</h2>
            <p class="mt-1 text-sm text-gray-600">Unduh dokumen terbaru.</p>
          </div>

          <a href="/rekapitulasi"
             class="text-sm font-semibold text-gray-700 hover:underline">
            Lihat
          </a>
        </div>

        <div class="p-4 space-y-3">
          <!-- Doc card -->
          <div class="rounded-xl border border-black/10 p-3">
            <div class="flex items-start gap-3">
              <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700 text-[14px]">
                PDF
              </div>

              <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold">SPKL Februari 2026</p>
                <p class="mt-1 text-xs text-gray-600">Terbit: 1 Mar 2026</p>

                <div class="mt-2 flex flex-col gap-2">
                  <a href="#"
                     class="inline-flex w-full items-center justify-center rounded-full px-3 py-1.25 text-sm font-semibold border border-black/15 hover:bg-gray-50">
                    Download
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="rounded-xl border border-black/10 p-3">
            <div class="flex items-start gap-3">
              <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700 text-[14px]">
                PDF
              </div>

              <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold">Laporan Lembur Februari 2026</p>
                <p class="mt-1 text-xs text-gray-600">Terbit: 1 Feb 2026</p>

                <div class="mt-2">
                  <a href="#"
                     class="inline-flex w-full items-center justify-center rounded-full px-3 py-1.25 text-sm font-semibold border border-black/15 hover:bg-gray-50">
                    Download
                  </a>
                </div>
              </div>
            </div>
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
  const userName = "{{session('user')['nama']}}";
  el.textContent = `${greeting}, ${userName}`;
</script>

@endsection
