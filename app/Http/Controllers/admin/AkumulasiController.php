<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkumulasiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);

        $query = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->leftJoin('m_rates as r', function ($join) {
                $join->on('p.golongan', '=', 'r.golongan')
                     ->on('t.hari', '=', 'r.day_type');
            })
            ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select(
                't.date', 't.hari',
                't.jam_mulai_disetujui', 't.jam_selesai_disetujui',
                't.jam_mulai', 't.jam_selesai',
                'p.nama', 'p.nip', 'p.golongan',
                'r.uang_lembur', 'r.uang_makan', 'r.pajak', 'r.terima',
                'mt.kode_tim', 'mt.nama_tim'
            )
            ->orderBy('t.date')
            ->orderBy('p.nama');

        // Filter tim
        if ($request->filled('tim')) {
            $query->where('t.tim_kode_tim', $request->tim);
        }

        // Filter pegawai
        if ($request->filled('pegawai')) {
            $query->where('t.submitted_by_NIP', $request->pegawai);
        }

        $akumulasi = $query->paginate(20)->appends($request->query());

        // Hitung durasi dan nominal per baris
        $akumulasi->getCollection()->transform(function ($item) {
            $mulai   = strtotime($item->jam_mulai_disetujui);
            $selesai = strtotime($item->jam_selesai_disetujui);
            $jam     = $mulai && $selesai ? (int) floor(($selesai - $mulai) / 3600) : 0;

            $item->jam_disetujui = $jam;
            $item->jam_diajukan  = $item->jam_mulai && $item->jam_selesai
                ? (int) floor((strtotime($item->jam_selesai) - strtotime($item->jam_mulai)) / 3600)
                : 0;

            // Hitung nominal berdasarkan jam
            $uangLembur = ($item->uang_lembur ?? 0) * $jam;
            $uangMakan  = $item->uang_makan ?? 0;
            $jumlah     = $uangLembur + $uangMakan;
            $pajak      = round($jumlah * (($item->pajak ?? 0) / 100));
            $terima     = $jumlah - $pajak;

            $item->total_uang_lembur = $uangLembur;
            $item->total_uang_makan  = $uangMakan;
            $item->total_jumlah      = $jumlah;
            $item->total_pajak       = $pajak;
            $item->total_terima      = $terima;

            return $item;
        });

        $tim = DB::table('m_tim')->select('kode_tim', 'nama_tim')->get();

        return view('admin.akumulasi', compact('akumulasi', 'bulan', 'tim'));
    }

    public function download(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AkumulasiExport($request->all()),
            'akumulasi_' . $bulan . '.xlsx'
        );
    }
}
