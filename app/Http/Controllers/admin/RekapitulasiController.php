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
        $nip = $request->get('nip_lama');

        [$tahun, $bln] = explode('-', $bulan);
        $monthDate = $tahun . '-' . $bln . '-01';

        // 1. Ambil transaksi approved di bulan tsb
        $transaksi = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->when($nip, fn($q) => $q->where('p.nip_lama', $nip))
            ->select(
                'p.id_pegawai',
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

        // 2. Hitung rekapitulasi per pegawai, groupBy 'nip_lama' (fix bug penumpukan)
        $rekapMap = $transaksi->groupBy('nip_lama')->map(function ($rows) use ($monthDate, $tahun, $bln) {
            $first      = $rows->first();
            $nama       = $first->nama;
            $nip        = $first->nip_lama;
            $id_pegawai = $first->id_pegawai;

            // Sesuaikan range kolom dengan struktur tabel
            $hb = array_fill(1, 12, 0);
            $hl = array_fill(1, 16, 0);

            $tanggalList = [];

            foreach ($rows as $row) {
                if (!$row->jam_mulai_disetujui || !$row->jam_selesai_disetujui) continue;

                $mulai   = strtotime($row->jam_mulai_disetujui);
                $selesai = strtotime($row->jam_selesai_disetujui);
                $durasi = (int) floor(($selesai - $mulai) / 3600);
                    if ($durasi <= 0) continue;
                $tgl     = (int) date('j', strtotime($row->date));

                $tanggalList[] = $tgl;

                if ($row->hari == 0) {
                    if (array_key_exists($durasi, $hb)) {
                        $hb[$durasi]++;
                    }
                } else {
                    if (array_key_exists($durasi, $hl)) {
                        $hl[$durasi]++;
                    }
                }
            }

            // Hitung total jam
            $jumlahHb = 0;
            for ($i = 1; $i <= 12; $i++) {
                if ($hb[$i] !== '') $jumlahHb += $i * $hb[$i];
            }

            $jumlahHl = 0;
            for ($i = 1; $i <= 16; $i++) {
                if ($hl[$i] !== '') $jumlahHl += $i * $hl[$i];
            }

            $tanggalStr = implode(', ', array_unique($tanggalList));

            return [
                'id_pegawai' => $id_pegawai,
                'nama'       => $nama,
                'nip'        => $nip,
                'month'      => $monthDate,
                'hb2'        => $hb[2],
                'hb3'        => $hb[3],
                'hb4'        => $hb[4],
                'hl2'        => $hl[2],
                'hl3'        => $hl[3],
                'hl4'        => $hl[4],
                'hl5'        => $hl[5],
                'hl6'        => $hl[6],
                'jumlah_hb'  => $jumlahHb,
                'jumlah_hl'  => $jumlahHl,
                'tanggal'    => !empty($tanggalList)
                    ? date('Y-m-d', strtotime(min($tanggalList) . ' day ' . $tahun . '-' . $bln))
                    : null,
                '_tanggal_display' => $tanggalStr,
            ];
        })->values();

        // 3. Upsert ke t_rekapitulasi (hapus bulan ini dulu, lalu insert baru)
        if (!$nip) {
            DB::table('t_rekapitulasi')
                ->where('month', $monthDate)
                ->delete();

            $insertData = $rekapMap->map(fn($r) => [
                'pegawai_id_pegawai' => $r['id_pegawai'],
                'month'              => $monthDate,
                'hb2'                => $r['hb2'] !== '' ? $r['hb2'] : null,
                'hb3'                => $r['hb3'] !== '' ? $r['hb3'] : null,
                'hb4'                => $r['hb4'] !== '' ? $r['hb4'] : null,
                'hl2'                => $r['hl2'] !== '' ? $r['hl2'] : null,
                'hl3'                => $r['hl3'] !== '' ? $r['hl3'] : null,
                'hl4'                => $r['hl4'] !== '' ? $r['hl4'] : null,
                'hl5'                => $r['hl5'] !== '' ? $r['hl5'] : null,
                'hl6'                => $r['hl6'] !== '' ? $r['hl6'] : null,
                'jumlah_hb'          => $r['jumlah_hb'],
                'jumlah_hl'          => $r['jumlah_hl'],
                'tanggal'            => null,
            ])->toArray();

            if (!empty($insertData)) {
                DB::table('t_rekapitulasi')->insert($insertData);
            }
        }

        // 4. Paginasi untuk tampilan (dari hasil hitung, bukan dari DB)
        $perPage = 10;
        $page    = $request->get('page', 1);
        $items   = $rekapMap->forPage($page, $perPage);

        $rekapitulasi = new LengthAwarePaginator(
            $items,
            $rekapMap->count(),
            $perPage,
            $page,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
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
