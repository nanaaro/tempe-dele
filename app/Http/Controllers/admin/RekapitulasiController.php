<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);

        $transaksi = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select(
                'p.nama', 'p.nip',
                't.date', 't.hari',
                't.jam_mulai_disetujui', 't.jam_selesai_disetujui'
            )
            ->orderBy('p.nama')
            ->orderBy('t.date')
            ->get();

        // Group per pegawai, hitung HB/HL per durasi
        $rekapitulasi = $transaksi->groupBy('nip')->map(function ($rows) {
            $nama   = $rows->first()->nama;
            $nip    = $rows->first()->nip;
            $hb     = [2=>0, 3=>0, 4=>0];
            $hl     = [2=>0, 3=>0, 4=>0, 5=>0, 6=>0];
            $tanggal = [];

            foreach ($rows as $row) {
                if (!$row->jam_mulai_disetujui || !$row->jam_selesai_disetujui) continue;

                $mulai   = strtotime($row->jam_mulai_disetujui);
                $selesai = strtotime($row->jam_selesai_disetujui);
                $durasi  = (int) floor(($selesai - $mulai) / 3600);
                $tgl     = (int) date('j', strtotime($row->date));

                $tanggal[] = $tgl;

                if ($row->hari == 0) {
                    if (isset($hb[$durasi])) $hb[$durasi]++;
                } else {
                    if (isset($hl[$durasi])) $hl[$durasi]++;
                }
            }

            $jumlahHb = ($hb[2]*2) + ($hb[3]*3) + ($hb[4]*4);
            $jumlahHl = ($hl[2]*2) + ($hl[3]*3) + ($hl[4]*4) + ($hl[5]*5) + ($hl[6]*6);

            return [
                'nama'      => $nama,
                'nip'       => $nip,
                'hb2'       => $hb[2], 'hb3' => $hb[3], 'hb4' => $hb[4],
                'hl2'       => $hl[2], 'hl3' => $hl[3], 'hl4' => $hl[4],
                'hl5'       => $hl[5], 'hl6' => $hl[6],
                'jumlah_hb' => $jumlahHb,
                'jumlah_hl' => $jumlahHl,
                'tanggal'   => implode(', ', array_unique(sort($tanggal) ? $tanggal : $tanggal)),
            ];
        })->values();

        $perPage = 10;
        $page    = $request->get('page', 1);
        $items   = $rekapitulasi->forPage($page, $perPage);

        $rekapitulasi = new LengthAwarePaginator(
            $items,
            $rekapitulasi->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.spkl', compact('rekapitulasi', 'bulan'));
    }
}
