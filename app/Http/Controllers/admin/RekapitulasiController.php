<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $nip   = $request->get('nip', '');
        [$tahun, $bln] = explode('-', $bulan);

        $transaksi = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->when($nip, fn($q) => $q->where('p.nip_lama', $nip))
            ->select(
                'p.nama',
                'p.nip_lama',
                't.date',
                't.hari',
                't.jam_mulai_disetujui',
                't.jam_selesai_disetujui'
            )
            ->orderBy('p.nama')
            ->orderBy('t.date')
            ->get();

        $rekapitulasi = $transaksi->groupBy('nip')->map(function ($rows) {
            $nama = $rows->first()->nama;
            $nip  = $rows->first()->nip_lama;

            $hb = array_fill(1, 12, 0);
            $hl = array_fill(1, 16, 0);
            $tanggal = [];

            foreach ($rows as $row) {
                if (!$row->jam_mulai_disetujui || !$row->jam_selesai_disetujui) {
                    continue;
                }

                $mulai   = strtotime($row->jam_mulai_disetujui);
                $selesai = strtotime($row->jam_selesai_disetujui);
                $durasi  = (int) floor(($selesai - $mulai) / 3600);

                $tgl = (int) date('j', strtotime($row->date));
                $tanggal[] = $tgl;

                if ($row->hari == 0) {
                    if (isset($hb[$durasi])) $hb[$durasi]++;
                } else {
                    if (isset($hl[$durasi])) $hl[$durasi]++;
                }
            }

            $jumlahHb = 0;
            for ($i = 1; $i <= 12; $i++) {
                $jumlahHb += $hb[$i] * $i;
            }

            $jumlahHl = 0;
            for ($i = 1; $i <= 16; $i++) {
                $jumlahHl += $hl[$i] * $i;
            }

            $result = [
                'nama' => $nama,
                'nip'  => $nip,
                'jumlah_hb' => $jumlahHb,
                'jumlah_hl' => $jumlahHl,
                'tanggal'   => implode(', ', array_unique($tanggal)),
            ];

            for ($i = 1; $i <= 12; $i++) {
                $result['hb' . $i] = $hb[$i];
            }

            for ($i = 1; $i <= 16; $i++) {
                $result['hl' . $i] = $hl[$i];
            }

            return $result;
        })->values();

        $perPage = 10;
        $page    = $request->get('page', 1);

        $items = $rekapitulasi->forPage($page, $perPage);

        $rekapitulasi = new LengthAwarePaginator(
            $items,
            $rekapitulasi->count(),
            $perPage,
            $page,
            [
                'path'  => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('admin.spkl', compact('rekapitulasi', 'bulan'));
    }

    public function downloadExcel(Request $request)
    {
        $bulan    = $request->get('bulan', now()->format('Y-m'));
        $filename = 'Rekapitulasi_Lembur_' . $bulan . '.xlsx';

        return Excel::download(
            new \App\Exports\RekapitulasiExport(['bulan' => $bulan]),
            $filename
        );
    }
}
