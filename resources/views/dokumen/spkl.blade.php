{{-- resources/views/dokumen/spkl.blade.php --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        margin: 0;
        padding: 20px 28px;
        color: #000;
    }

    .page {
        width: 100%;
    }

    .surat-header {
        display: flex
        align-items: center;
        margin-bottom: 10px;
        gap: 12px;
    }

    .surat-header td {
        border: none;
        vertical-align: start
        text-align: left;
    }

    .logo-cell {
        width: 90px;
        text-align: left;
    }

    .logo-box {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #999;
        font-size: 9pt;
        color: #666;
        text-align: center;
        line-height: 1.2;
    }

    .logo-box img {
        width: 72px;
        height: 72px;
        object-fit: contain;
    }

    .instansi-cell {
        text-align: center;
        padding-right: 90px;
    }

    .instansi {
        font-family: Arial, sans-serif;
        font-size: 18pt;
        font-weight: bold;
        font-style: italic;
        color: #38B6FF;
        line-height: 1.25;
        text-transform: uppercase;
        text-align: left;
    }

    .header {
        text-align: center;
        margin-bottom: 18px;
    }

    .title {
        font-size: 14pt;
        font-weight: bold;
        text-decoration: underline;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .nomor {
        margin-bottom: 15px;
    }

    .pembuka {
        text-align: justify;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        border: 1px solid black;
        padding: 6px 8px;
        vertical-align: top;
    }

    th {
        text-align: center;
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .text-center {
        text-align: center;
    }

    .ttd-table {
        width: 100%;
        border: none;
        margin-top: 28px;
    }

    .ttd-table td {
        border: none;
        vertical-align: top;
        width: 50%;
    }

    .ttd-box {
        text-align: center;
        line-height: 1.5;
    }

    .ttd-space {
        height: 70px;
    }

    .nip-text {
        margin-top: 2px;
    }
</style>
</head>
<body>
<div class="page">

    {{-- HEADER INSTANSI + LOGO --}}
    <table class="surat-header">
        <tr>
            <td class="logo-cell">
                <div class="logo-box" style="border:none;">
                    <img src="{{ public_path('images/Logo-Badan-Pusat-Statistik-BPS.png') }}" alt="Logo BPS">
                </div>
            </td>
            <td class="instansi-cell">
                <div class="instansi">
                    BADAN PUSAT STATISTIK<br>
                    PROVINSI JAWA TENGAH
                </div>
            </td>
        </tr>
    </table>

    <div style="width: 100%; border-top: 2px solid black; margin: 6px 0;"></div>

    {{-- JUDUL SURAT --}}
    <div class="header">
        <div class="title">SURAT PERINTAH KERJA LEMBUR</div>
        <div class="nomor">Nomor: {{ $nomorSurat }}</div>
    </div>

    {{-- PEMBUKA --}}
    <div class="pembuka">
        &nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan adanya penyelesaian pekerjaan yang dilakukan di luar jam kerja (lembur) pada bulan {{ $bulanLabel }} Tahun {{ $tahun }}, dengan ini kami memerintahkan pegawai tersebut di bawah ini untuk menyelesaikan pekerjaan yang dimaksud.
    </div>

    {{-- TABEL --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:30%">Nama Pegawai/NIP</th>
                <th style="width:20%">Bulan {{ $bulanLabel }}</th>
                <th>Uraian Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>
                    {{ $p->nama }}<br>
                    <small>{{ $p->nip }}</small>
                </td>
                <td class="text-center">{{ $p->tanggal_lembur }}</td>
                <td style="white-space: pre-line;">{{ $p->uraian }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <table class="ttd-table">
        <tr>
            <td class="ttd-box">
                <div style="height: 60px;">
                Pejabat Pembuat Komitmen<br>
                BPS Provinsi Jawa Tengah
                </div>
                <div class="ttd-space"></div>
                <span class="nama-pejabat">{{ $ppk->nama ?? '' }}</span><br>
                <div class="nip-text">NIP. {{ $ppk->nip_lama ?? '' }}</div>
            </td>
            <td class="ttd-box">
                Mengetahui, {{ $tanggalTtd }}<br>
                a.n. Kepala BPS Provinsi Jawa Tengah<br>
                Kepala Bagian Umum
                <br>
                <div class="ttd-space"></div>
                <span class="nama-pejabat">{{ $kbu->nama ?? '' }}</span><br>
                <div class="nip-text">NIP {{ $kbu->nip_lama ?? '' }}</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
