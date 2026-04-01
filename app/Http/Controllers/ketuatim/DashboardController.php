<?php

namespace App\Http\Controllers\ketuatim;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $nipKetua = session('user')['nip'];

        // 5 pengajuan terbaru dari anggota tim
        $pengajuan = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.approver_employee_id', $nipKetua)
            ->select('t.*', 'p.nama as nama_pegawai')
            ->orderBy('t.submitted_at', 'desc')
            ->limit(3)
            ->get();

        // Lembur hari ini yang approved
        $lemburHariIni = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.approver_employee_id', $nipKetua)
            ->whereDate('t.date', today())
            ->where('t.status', 'approved')
            ->select('p.nama as nama_pegawai', 't.jam_mulai_disetujui', 't.jam_selesai_disetujui')
            ->get();

        return view('ketua-tim.dashboard', compact('pengajuan', 'lemburHariIni'));
    }
}
