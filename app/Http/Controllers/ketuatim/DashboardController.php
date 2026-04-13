<?php

namespace App\Http\Controllers\ketuatim;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $nipKetua = session('user')['nip'];
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // --- Statistik pengajuan tim bulan ini ---
        $stats = [
            'total'     => DB::table('t_transaksi')->where('approver_employee_id', $nipKetua)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->count(),
            'disetujui' => DB::table('t_transaksi')->where('approver_employee_id', $nipKetua)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'approved')->count(),
            'diproses'  => DB::table('t_transaksi')->where('approver_employee_id', $nipKetua)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'pending')->count(),
            'ditolak'   => DB::table('t_transaksi')->where('approver_employee_id', $nipKetua)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'rejected')->count(),
        ];

        // --- 5 pengajuan terbaru dari anggota tim ---
        $pengajuan = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.approver_employee_id', $nipKetua)
            ->select('t.*', 'p.nama as nama_pegawai')
            ->orderBy('t.submitted_at', 'desc')
            ->limit(3)
            ->get();

        // --- Lembur hari ini yang approved ---
        $lemburHariIni = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.approver_employee_id', $nipKetua)
            ->whereDate('t.date', today())
            ->where('t.status', 'approved')
            ->select('p.nama as nama_pegawai', 't.jam_mulai_disetujui', 't.jam_selesai_disetujui')
            ->get();

        return view('ketua-tim.dashboard', compact('stats', 'pengajuan', 'lemburHariIni'));
    }
}
