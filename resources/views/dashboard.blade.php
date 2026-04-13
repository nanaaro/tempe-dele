@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Hero --}}
<section class="mx-auto max-w-7xl py-8 px-4 sm:px-8 md:px-10 lg:px-20">
  <div
    class="relative flex w-full flex-col items-center bg-[#f9b800]
           rounded-[30px] px-10 py-10 lg:py-12 lg:flex-row
           overflow-visible min-h-55"
  >
    <div class="text-center md:text-left lg:flex-1 max-w-xl">
      <h2 id="greeting" class="mb-4 text-2xl lg:text-3xl font-semibold leading-tight">
        Selamat Datang, {{ session('user')['nama'] }}
      </h2>
      <p class="text-[18px] leading-relaxed text-gray-900">
        Kelola pengajuan lembur dan pantau perkembangannya dengan lebih mudah.
      </p>
    </div>

    <img
      class="order-2 w-full max-w-130 object-contain
             lg:order-0 lg:absolute lg:right-0 lg:-top-16
             drop-shadow-xl"
      src="{{ asset('images/2.svg') }}"
      alt=""
    />
  </div>
</section>

{{-- Konten Utama --}}
<section class="mx-auto max-w-7xl px-4 sm:px-8 md:px-10 lg:px-20 pb-10">

  {{-- Header --}}
  <div class="mb-6">
    <h1 class="text-2xl font-semibold tracking-tight">Aktivitas</h1>
    <p class="mt-1 text-sm text-gray-600">Ringkasan aktivitas lembur dan pengajuan terbaru.</p>
  </div>

  {{-- Metric Cards --}}
  <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="rounded-2xl bg-gray-50 p-5">
      <p class="text-xs text-gray-500 mb-2">Total pengajuan</p>
      <p class="text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
      <p class="mt-1 text-xs text-gray-400">Bulan ini</p>
    </div>
    <div class="rounded-2xl bg-gray-50 p-5">
      <p class="text-xs text-gray-500 mb-2">Disetujui</p>
      <p class="text-3xl font-semibold text-green-700">{{ $stats['disetujui'] }}</p>
      <p class="mt-1 text-xs text-gray-400">Pengajuan bulan ini</p>
    </div>
    <div class="rounded-2xl bg-gray-50 p-5">
      <p class="text-xs text-gray-500 mb-2">Diproses</p>
      <p class="text-3xl font-semibold text-yellow-700">{{ $stats['diproses'] }}</p>
      <p class="mt-1 text-xs text-gray-400">Menunggu review</p>
    </div>
    <div class="rounded-2xl bg-gray-50 p-5">
      <p class="text-xs text-gray-500 mb-2">Ditolak</p>
      <p class="text-3xl font-semibold text-red-700">{{ $stats['ditolak'] }}</p>
      <p class="mt-1 text-xs text-gray-400">Pengajuan bulan ini</p>
    </div>
  </div>

  {{-- Grid Utama: dua kartu sebelahan --}}
  <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- Pengajuan Terbaru --}}
    <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
      <div class="flex flex-col gap-3 border-b border-black/10 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-lg font-semibold">Pengajuan terbaru</h2>
          <p class="mt-1 text-sm text-gray-600">5 pengajuan lembur terakhir kamu.</p>
        </div>
        <a href="{{ route('lembur') }}"
           class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold border border-black/15 hover:bg-gray-50">
          Lihat semua
        </a>
      </div>

      <div class="divide-y divide-black/5">
        @forelse ($pengajuanTerbaru as $item)
          <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
              <p class="text-sm font-semibold">
                {{ \Carbon\Carbon::parse($item->date)->translatedFormat('D, d M Y') }}
              </p>
              <p class="mt-1 text-sm text-gray-500">
                @if ($item->status === 'approved' && $item->jam_mulai_disetujui)
                  {{ substr($item->jam_mulai_disetujui, 0, 5) }} &ndash; {{ substr($item->jam_selesai_disetujui, 0, 5) }}
                @elseif ($item->jam_mulai)
                  {{ substr($item->jam_mulai, 0, 5) }} &ndash; {{ substr($item->jam_selesai, 0, 5) }}
                @else
                  -
                @endif
              </p>
            </div>
            <div class="flex items-center gap-3">
              @if ($item->status === 'approved')
                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Disetujui</span>
              @elseif ($item->status === 'pending')
                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">Diproses</span>
              @elseif ($item->status === 'rejected')
                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">Ditolak</span>
              @endif
              <span class="text-sm text-gray-400">›</span>
            </div>
          </div>
        @empty
          <div class="p-5 text-sm text-gray-400 text-center">
            Belum ada pengajuan lembur.
          </div>
        @endforelse
      </div>
    </div>

    {{-- Jadwal Lembur Mendatang --}}
    <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
      <div class="flex flex-col gap-3 border-b border-black/10 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-lg font-semibold">Jadwal lembur mendatang</h2>
          <p class="mt-1 text-sm text-gray-600">Lembur disetujui yang belum terlaksana.</p>
        </div>
        <a href="{{ route('lembur') }}"
           class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold border border-black/15 hover:bg-gray-50">
          Lihat semua
        </a>
      </div>

      <div class="divide-y divide-black/5">
        @forelse ($jadwalMendatang as $item)
          @php
            $selisihHari = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($item->date), false);
          @endphp
          <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-[#f9b800]/20 text-center">
                <span class="text-xs font-semibold text-yellow-800 leading-none">
                  {{ \Carbon\Carbon::parse($item->date)->translatedFormat('M') }}
                </span>
                <span class="text-lg font-semibold text-yellow-900 leading-tight">
                  {{ \Carbon\Carbon::parse($item->date)->format('d') }}
                </span>
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold">
                  {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                  @if ($item->jam_mulai_disetujui)
                    {{ substr($item->jam_mulai_disetujui, 0, 5) }} &ndash; {{ substr($item->jam_selesai_disetujui, 0, 5) }}
                  @else
                    -
                  @endif
                </p>
              </div>
            </div>
            <div class="flex-shrink-0">
              @if ($selisihHari === 1)
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">Besok</span>
              @elseif ($selisihHari <= 3)
                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">{{ $selisihHari }} hari lagi</span>
              @else
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">{{ $selisihHari }} hari lagi</span>
              @endif
            </div>
          </div>
        @empty
          <div class="p-5 text-sm text-gray-400 text-center">
            Tidak ada jadwal lembur mendatang.
          </div>
        @endforelse
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
  const el = document.getElementById("greeting");
  if (el) {
    el.textContent = `${getGreeting(new Date().getHours())}, {{ session('user')['nama'] ?? 'User' }}`;
  }
</script>

@endsection
