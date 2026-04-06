<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarHadirController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $query = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('t.status', 'approved')
            ->whereDate('t.date', $tanggal)
            ->select(
                't.date', 't.jam_mulai_disetujui', 't.jam_selesai_disetujui',
                'p.nama', 'p.nip',
                'mt.kode_tim', 'mt.nama_tim'
            )
            ->orderBy('p.nama');

        if ($request->filled('tim')) {
            $query->where('t.tim_kode_tim', $request->tim);
        }

        if ($request->filled('nip')) {
            $query->where('p.nip', $request->nip);
        }

        $daftarHadir = $query->get();
        $tim = DB::table('m_tim')->select('kode_tim', 'nama_tim')->get();

        return view('admin.daftar_hadir', compact('daftarHadir', 'tanggal', 'tim'));
    }

    public function download(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $query = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('t.status', 'approved')
            ->whereDate('t.date', $tanggal)
            ->select(
                't.date', 't.jam_mulai_disetujui', 't.jam_selesai_disetujui',
                'p.nama', 'p.nip',
                'mt.kode_tim', 'mt.nama_tim'
            )
            ->orderBy('p.nama');

        if ($request->filled('tim')) $query->where('t.tim_kode_tim', $request->tim);
        if ($request->filled('nip')) $query->where('p.nip', $request->nip);

        $daftarHadir  = $query->get();
        $tanggalLabel = \Carbon\Carbon::parse($tanggal)->translatedFormat('d/m/Y');
        $namaTim      = $request->filled('tim')
            ? DB::table('m_tim')->where('kode_tim', $request->tim)->value('nama_tim') ?? ''
            : '';
        $kbu = DB::table('m_pejabat')->where('jabatan', 'Kepala BPS')->where('status', 'aktif')->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'dokumen.daftar_hadir',
            compact('daftarHadir', 'tanggalLabel', 'namaTim', 'kbu')
        )->setPaper('a4', 'portrait');

        return $pdf->download('daftar_hadir_' . $tanggal . '.pdf');
    }
}
