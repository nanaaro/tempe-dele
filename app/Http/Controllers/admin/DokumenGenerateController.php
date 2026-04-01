<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DokumenGenerateController extends Controller
{
    private function getPejabat()
    {
        $pejabat = DB::table('m_pejabat')->where('status', 'aktif')->get();
        $ppk = $pejabat->firstWhere('jabatan', 'PPK');
        $kbu = $pejabat->firstWhere('jabatan', 'Kepala BPS');
        return [$ppk, $kbu];
    }

    private function getNomorSurat(string $bulan): string
    {
        $dt = Carbon::parse($bulan . '-01');
        $urutan = DB::table('t_dokumen')
            ->whereYear('generated_at', $dt->year)
            ->whereMonth('generated_at', $dt->month)
            ->count() + 1;
        $nomorUrut = str_pad($urutan, 5, '0', STR_PAD_LEFT);
        return "558.1/{$nomorUrut}/RT.512/{$dt->year}";
    }

    public function spkl(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);
        $dt = Carbon::parse($bulan . '-01');

        $existing = DB::table('t_dokumen')
            ->where('periode', $bulan)
            ->where('type', 'spkl')
            ->first();
        if ($existing) {
            return redirect()->route('admin.dokumen')->with('info', 'SPKL sudah pernah digenerate.');
        }

        $transaksi = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select('p.nama', 'p.nip', 'p.nip_lama', 't.date', 't.uraian')
            ->orderBy('p.nama')
            ->get();

        $pegawai = $transaksi->groupBy('nip')->map(function ($rows) {
            $first   = $rows->first();
            $tanggal = $rows->pluck('date')
                ->map(fn($d) => (int) date('j', strtotime($d)))
                ->sort()->values()->implode(', ');
            $uraian  = $rows->pluck('uraian')->unique()->implode('; ');
            return (object) [
                'nama'           => $first->nama,
                'nip'            => $first->nip,
                'tanggal_lembur' => $tanggal,
                'uraian'         => $uraian,
            ];
        })->values();

        [$ppk, $kbu] = $this->getPejabat();
        $nomorSurat  = $request->get('nomor_surat', $this->getNomorSurat($bulan));
        $bulanLabel  = $dt->translatedFormat('F');
        $tahun       = $dt->year;
        $tanggalTtd  = $dt->translatedFormat('d F Y');

        $pdf = Pdf::loadView('dokumen.spkl', compact('pegawai', 'ppk', 'kbu', 'nomorSurat', 'bulanLabel', 'tahun', 'tanggalTtd'))->setPaper('a4', 'portrait');

        DB::table('t_dokumen')->insert([
            'type'         => 'spkl',
            'periode'      => $bulan,
            'generated_at' => now(),
            'file_blob'    => $pdf->output(),
        ]);

        return redirect()->route('admin.dokumen')->with('success', 'SPKL berhasil digenerate.');
    }

    public function laporan(Request $request, string $jenis)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);
        $dt = Carbon::parse($bulan . '-01');

        $existing = DB::table('t_dokumen')
            ->where('periode', $bulan)
            ->where('type', 'laporan_' . $jenis)
            ->first();
        if ($existing) {
            return redirect()->route('admin.dokumen')->with('info', 'Laporan sudah pernah digenerate.');
        }

        $query = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select('p.nama', 'p.nip', 'p.nip_lama', 't.date', 't.uraian')
            ->orderBy('p.nama')
            ->orderBy('t.date');

        if ($jenis === 'pns') {
            $query->whereRaw('LENGTH(p.nip_lama) = 9');
        } else {
            $query->whereRaw('LENGTH(p.nip_lama) != 9');
        }

        $pegawai = $query->get()->groupBy('nip')->map(function ($rows) {
            $first   = $rows->first();
            $tanggal = $rows->pluck('date')
                ->map(fn($d) => (int) date('j', strtotime($d)))
                ->sort()->values()->implode(', ');
            $uraian  = $rows->pluck('uraian')->unique()->filter()->implode('; ');
            return (object) [
                'nama'    => $first->nama,
                'nip'     => $first->nip,
                'tanggal' => $tanggal,
                'uraian'  => $uraian,
            ];
        })->values();

        [$ppk, $kbu] = $this->getPejabat();
        $bulanLabel  = $dt->translatedFormat('F');
        $tahun       = $dt->year;

        $pdf = Pdf::loadView('dokumen.laporan', compact('pegawai', 'kbu', 'bulanLabel', 'tahun', 'jenis'))->setPaper('a4', 'portrait');

        DB::table('t_dokumen')->insert([
            'type'         => 'laporan_' . $jenis,
            'periode'      => $bulan,
            'generated_at' => now(),
            'file_blob'    => $pdf->output(),
        ]);

        return redirect()->route('admin.dokumen')->with('success', 'Laporan berhasil digenerate.');
    }
}
