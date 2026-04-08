<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumenViewController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));

        $dokumenList = DB::table('t_dokumen')->orderBy('periode', 'desc')->get();

        $periodeTransaksi = DB::table('t_transaksi')
            ->where('status', 'approved')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as periode")
            ->distinct()
            ->pluck('periode');

        $periodeDokumen = $dokumenList->pluck('periode');
        $allPeriode = $periodeTransaksi->merge($periodeDokumen)
        ->unique()
        ->sortDesc()
        ->values();

    $isFiltered = $request->has('bulan');

    $periodeList = $isFiltered
        ? $allPeriode->filter(fn($p) => $p === $bulan)
        : $allPeriode;

        $perPage  = 12;
        $page     = $request->get('page', 1);
        $items    = $periodeList->forPage($page, $perPage);

        $periodeList = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $periodeList->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $pejabat = DB::table('m_pejabat')->where('status', 'aktif')->get();
        $ppk = $pejabat->firstWhere('jabatan', 'PPK');
        $kbps = $pejabat->firstWhere('jabatan', 'Kepala BPS');
        $kbu = $pejabat->firstWhere('jabatan', 'Kepala Bagian Umum');

        return view('admin.dokumen', compact('periodeList', 'dokumenList', 'ppk', 'kbps', 'kbu', 'bulan'));
    }

    public function hapus(Request $request)
    {
        $bulan = $request->get('bulan');
        DB::table('t_dokumen')->where('periode', $bulan)->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function hapusSatu($id)
    {
        DB::table('t_dokumen')->where('id_dokumen', $id)->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function view($id)
    {
        $dokumen = DB::table('t_dokumen')->where('id_dokumen', $id)->first();
        if (!$dokumen || !$dokumen->file_blob) abort(404);

        $timestamp = $dokumen->generated_at
            ? \Carbon\Carbon::parse($dokumen->generated_at)->format('Ymd_His')
            : now()->format('Ymd_His');

        $namaFile = match($dokumen->type) {
            'spkl'         => "SPKL_{$dokumen->periode}_{$timestamp}.pdf",
            'laporan_pns'  => "Laporan_Lembur_PNS_{$dokumen->periode}_{$timestamp}.pdf",
            'laporan_pppk' => "Laporan_Lembur_PPPK_{$dokumen->periode}_{$timestamp}.pdf",
            default        => "Dokumen_{$dokumen->periode}_{$timestamp}.pdf",
        };

        return response($dokumen->file_blob, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$namaFile}\"",
        ]);
    }
}
