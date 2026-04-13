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

  {{-- Main Content --}}
  <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

    {{-- Kiri: Pengajuan Lembur --}}
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
            @forelse ($pengajuan as $p)
              <tr class="hover:bg-slate-50 transition">
                <td class="px-6 py-4 font-medium text-slate-800">{{ $p->nama_pegawai }}</td>
                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->date)->translatedFormat('d F Y') }}</td>
                <td class="px-6 py-4">
                  {{ $p->jam_mulai ? substr($p->jam_mulai, 0, 5) . ' - ' . substr($p->jam_selesai, 0, 5) : '-' }}
                </td>
                <td class="px-6 py-4">
                  @if ($p->status === 'pending')
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">Menunggu</span>
                  @elseif ($p->status === 'approved')
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600">Disetujui</span>
                  @elseif ($p->status === 'rejected')
                    <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-medium text-rose-600">Ditolak</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">Belum ada pengajuan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Kanan: Lembur Hari Ini --}}
    <div class="xl:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-100">
      <div class="px-5 py-5 border-b border-slate-100">
        <h3 class="text-lg font-semibold text-slate-800">Lembur Hari Ini</h3>
        <p class="text-sm text-slate-500">Pegawai yang sedang / akan lembur hari ini</p>
      </div>

      <div class="p-5 space-y-4">
        @forelse ($lemburHariIni as $l)
          <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-800">{{ $l->nama_pegawai }}</p>
            <span class="text-sm text-slate-500">
              {{ $l->jam_mulai_disetujui ? substr($l->jam_mulai_disetujui, 0, 5) . ' - ' . substr($l->jam_selesai_disetujui, 0, 5) : '-' }}
            </span>
          </div>
        @empty
          <p class="text-sm text-slate-400">Tidak ada lembur hari ini.</p>
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
