{{-- resources/views/dokumen/laporan.blade.php --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 11pt; margin: 0; padding: 20px; }
    .title { text-align: center; font-size: 13pt; font-weight: bold; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid black; padding: 6px 8px; }
    th { text-align: center; background-color: #f0f0f0; }
    .ttd-box { text-align: center; margin-top: 30px; float: right; width: 40%; }
    .ttd-space { height: 70px; }
    .clearfix::after { content: ''; display: block; clear: both; }
</style>
</head>
<body>

<div class="title">
    LAPORAN HASIL KERJA LEMBUR BULAN {{ strtoupper($bulanLabel) }} TAHUN {{ $tahun }}<br>
</div>

<table>
    <thead>
        <tr>
            <th style="width:5%">No</th>
            <th style="width:30%">Nama Pegawai/NIP</th>
            <th style="width:20%">Tanggal Lembur<br><small>Bulan {{ $bulanLabel }}</small></th>
            <th>Uraian Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pegawai as $i => $p)
        <tr>
            <td style="text-align:center">{{ $i + 1 }}</td>
            <td>{{ $p->nama }}<br><small>{{ $p->nip_lama }}</small></td>
            <td style="text-align:center">{{ $p->tanggal }}</td>
            <td style="white-space: pre-line;">{{ $p->uraian }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="clearfix">
    <div class="ttd-box">
        Mengetahui,<br>
        Kepala Bagian Umum<br>
        <div class="ttd-space"></div>
        <span class="nama-pejabat">{{ $kbu->nama ?? '' }}</span>
        {{-- NIP {{ $kbu->nip_lama ?? '' }} --}}
    </div>
</div>

</body>
</html>
