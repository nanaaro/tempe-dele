@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- Hero --}}
<section class="mx-auto max-w-7xl py-8 px-4 sm:px-8 md:px-10 lg:px-20">
  <div class="relative flex flex-col lg:flex-row items-center bg-[#f9b800]
              rounded-[30px] px-8 py-10 lg:py-12 overflow-visible">

    <!-- Teks -->
    <div class="text-center lg:text-left lg:flex-1 lg:pr-[440px]">
      <h2 class="mb-3 text-2xl lg:text-3xl font-semibold leading-tight">
        Selamat Datang, {{ session('user')['nama'] }}
      </h2>
      <p class="text-[17px] leading-relaxed text-gray-900">
        Kelola pengajuan lembur dan pantau perkembangannya dengan lebih mudah.
      </p>
    </div>

    <img
      class="mt-6 w-full max-w-[300px] object-contain drop-shadow-xl
             lg:mt-0 lg:absolute lg:right-0 lg:-top-12 lg:max-w-[460px]"
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
      <p class="text-xs text-gray-500 mb-2">Diproses</p>
      <p class="text-3xl font-semibold text-yellow-700">{{ $stats['diproses'] }}</p>
      <p class="mt-1 text-xs text-gray-400">Menunggu review</p>
    </div>
    <div class="rounded-2xl bg-gray-50 p-5">
      <p class="text-xs text-gray-500 mb-2">Disetujui</p>
      <p class="text-3xl font-semibold text-green-700">{{ $stats['disetujui'] }}</p>
      <p class="mt-1 text-xs text-gray-400">
        Pengajuan bulan ini
      </p>
    </div>
    <div class="rounded-2xl bg-gray-50 p-5">
      <p class="text-xs text-gray-500 mb-2">Ditolak</p>
      <p class="text-3xl font-semibold text-red-700">{{ $stats['ditolak'] }}</p>
      <p class="mt-1 text-xs text-gray-400">
        Pengajuan bulan ini
      </p>
    </div>
  </div>

  {{-- Grid Utama --}}
  <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

    {{-- Kolom Kiri (8/12) --}}
    <div class="lg:col-span-8 flex flex-col gap-6">

      {{-- Lembur Hari Ini --}}
      <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-black/10 p-5 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h2 class="text-lg font-semibold">Lembur hari ini</h2>
            <p class="mt-1 text-sm text-gray-600">
              {{ now()->translatedFormat('l, d F Y') }}
              &mdash; {{ $lemburHariIni->count() }} karyawan
            </p>
          </div>
          <a href="{{ route('admin.daftar_hadir') }}"
             class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold border border-black/15 hover:bg-gray-50">
            Lihat semua
          </a>
        </div>

        <div class="divide-y divide-black/5">
          @forelse ($lemburHariIni as $item)
            <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
              <div class="min-w-0">
                <p class="text-sm font-semibold">{{ $item->nama_pegawai }}</p>
                <p class="mt-1 text-sm text-gray-500">
                  {{ $item->nama_tim ?? '-' }} &middot;
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
              </div>
            </div>
          @empty
            <div class="p-5 text-sm text-gray-400 text-center">
              Tidak ada pengajuan lembur hari ini.
            </div>
          @endforelse
        </div>
      </div>

      {{-- Manajemen Dokumen --}}
      <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-black/10 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
            <h2 class="text-lg font-semibold">Manajemen dokumen</h2>
            <p class="mt-1 text-sm text-gray-600">SPKL &amp; laporan lembur</p>
            </div>
            <a href="{{ route('admin.dokumen') }}"
            class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold border border-black/15 hover:bg-gray-50">
            Kelola
            </a>
        </div>

        <div class="divide-y divide-black/5">
        @php
            $docSpkl = $dokumen->where('type', 'spkl')->first();
            $docPns  = $dokumen->where('type', 'laporan_pns')->first();
            $docPppk = $dokumen->where('type', 'laporan_pppk')->first();

            $types = [
                ['label' => 'SPKL',         'doc' => $docSpkl],
                ['label' => 'Laporan PNS',  'doc' => $docPns],
                ['label' => 'Laporan PPPK', 'doc' => $docPppk],
            ];
        @endphp

        @foreach ($types as $item)
            @php $dok = $item['doc']; @endphp
            <div class="flex items-center gap-4 p-5 {{ is_null($dok) || is_null($dok->file_blob) ? 'bg-amber-50' : '' }}">

                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl
                    {{ is_null($dok) || is_null($dok->file_blob) ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}
                    text-xs font-semibold">
                    PDF
                </div>

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                        {{ $item['label'] }} - {{ now()->translatedFormat('F Y') }}
                    </p>

                    <p class="mt-0.5 text-xs {{ is_null($dok) || is_null($dok->file_blob) ? 'text-amber-600' : 'text-gray-500' }}">
                        {{ is_null($dok) || is_null($dok->file_blob)
                            ? 'Belum di-generate'
                            : 'Terbit: ' . \Carbon\Carbon::parse($dok->generated_at)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div class="flex-shrink-0">
                    @if (!is_null($dok) && !is_null($dok->file_blob))
                        <a href="{{ route('admin.dokumen.view', $dok->id_dokumen) }}"
                        class="inline-flex items-center justify-center rounded-full px-3 py-1.5 text-xs font-semibold border border-black/15 hover:bg-gray-50">
                            Preview
                        </a>
                    @endif
                </div>

            </div>
        @endforeach
        </div>
      </div>

    </div>

    {{-- Kolom Kanan (4/12) --}}
    <div class="lg:col-span-4 flex flex-col gap-6">

      {{-- Notifikasi --}}
      <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="flex items-start justify-between border-b border-black/10 p-5">
          <div>
            <h2 class="text-lg font-semibold">Notifikasi</h2>
            <p class="mt-1 text-sm text-gray-600">Item yang perlu perhatian</p>
          </div>
          @if ($notifikasi->count() > 0)
            <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">
              {{ $notifikasi->count() }}
            </span>
          @endif
        </div>

        <div class="divide-y divide-black/5">
          @forelse ($notifikasi as $notif)
            <div class="flex items-start gap-3 p-4">
              <span class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full
                @if ($notif['level'] === 'danger') bg-red-500
                @elseif ($notif['level'] === 'warning') bg-yellow-400
                @else bg-blue-400
                @endif">
              </span>
              <div>
                <p class="text-sm text-gray-800">{{ $notif['pesan'] }}</p>
                <p class="mt-0.5 text-xs text-gray-400">{{ $notif['waktu'] }}</p>
              </div>
            </div>
          @empty
            <div class="p-5 text-sm text-gray-400 text-center">
              Semua sudah beres!
            </div>
          @endforelse
        </div>
      </div>

      {{-- Ringkasan Bulan Ini --}}
      <div class="rounded-2xl border border-black/10 bg-white shadow-sm">
        <div class="border-b border-black/10 p-5">
          <h2 class="text-lg font-semibold">Ringkasan bulan ini</h2>
          <p class="mt-1 text-sm text-gray-600">{{ now()->translatedFormat('F Y') }}</p>
        </div>

        <div class="divide-y divide-black/5 p-2">
          <div class="flex items-center justify-between px-3 py-3">
            <span class="text-sm text-gray-600">Presensi lengkap</span>
            @if ($ringkasan['presensi_lengkap'])
              <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Sudah</span>
            @else
              <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">Belum</span>
            @endif
          </div>
          <div class="flex items-center justify-between px-3 py-3">
            <span class="text-sm text-gray-600">SPKL bulan ini</span>
            @if ($ringkasan['spkl_generated'])
              <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Sudah</span>
            @else
              <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">Belum</span>
            @endif
          </div>
          <div class="flex items-center justify-between px-3 py-3">
            <span class="text-sm text-gray-600">Laporan bulan ini</span>
            @if ($ringkasan['laporan_generated'])
              <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Sudah</span>
            @else
              <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">Belum</span>
            @endif
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
  const el = document.getElementById("greeting");
  if (el) {
    el.textContent = `${getGreeting(new Date().getHours())}, {{ session('user')['nama'] ?? 'Admin' }}`;
  }
</script>

@endsection
