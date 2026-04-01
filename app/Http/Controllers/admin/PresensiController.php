<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index()
    {
        return view('admin.presensi');
    }

    // Endpoint list tim
    public function getTim()
    {
        $tim = DB::table('m_tim')
            ->where('status', 'Aktif')
            ->select('kode_tim', 'nama_tim')
            ->get();

        return response()->json($tim);
    }

    // Endpoint list pegawai, filter by tim kalau ada
    public function getPegawai(Request $request)
    {
        $kodeTim = $request->query('kode_tim');

        if ($kodeTim) {
            $pegawai = DB::table('m_pegawai')
                ->join('t_anggota_tim', 'm_pegawai.id_pegawai', '=', 't_anggota_tim.pegawai_id_pegawai')
                ->where('t_anggota_tim.tim_kode_tim', $kodeTim)
                ->select('m_pegawai.id_pegawai', 'm_pegawai.nama', 'm_pegawai.nip', 'm_pegawai.nip_lama')
                ->get();
        } else {
            $pegawai = DB::table('m_pegawai')
                ->select('id_pegawai', 'nama', 'nip', 'nip_lama')
                ->get();
        }

        return response()->json($pegawai);
    }

    public function getAllPegawai()
    {
        $pegawai = \DB::table('m_pegawai')
            ->select('id_pegawai', 'nama', 'nip')
            ->orderBy('nama')
            ->get();
        return response()->json($pegawai);
    }
}
