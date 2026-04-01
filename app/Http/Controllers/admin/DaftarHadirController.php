<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarHadirController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);

        $query = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('t.status', 'approved')
            ->whereYear('t.date', $tahun)
            ->whereMonth('t.date', $bln)
            ->select(
                't.date', 't.jam_mulai_disetujui', 't.jam_selesai_disetujui',
                'p.nama', 'p.nip',
                'mt.kode_tim', 'mt.nama_tim'
            )
            ->orderBy('t.date')
            ->orderBy('p.nama');

        if ($request->filled('tim')) {
            $query->where('t.tim_kode_tim', $request->tim);
        }

        $daftarHadir = $query->get();
        $tim = DB::table('m_tim')->select('kode_tim', 'nama_tim')->get();

        return view('admin.daftar_hadir', compact('daftarHadir', 'bulan', 'tim'));
    }
}
