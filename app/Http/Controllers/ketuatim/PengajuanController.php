<?php

namespace App\Http\Controllers\ketuatim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        $nipKetua = session('user')['nip'];

        $tim = DB::table('m_tim')->where('nipbaru_ketua', $nipKetua)->first();

        $pengajuan = DB::table('t_transaksi as t')
        ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
        ->leftJoin('m_tim as mt', 't.tim_kode_tim', '=', 'mt.kode_tim')
        ->where('t.approver_employee_id', $nipKetua)
        ->select([
            't.*',
            'p.nama as nama_pegawai',
            'p.nip as nip_pegawai',
            'p.nip_lama',
            'mt.nama_tim',
            DB::raw('EXISTS(
                SELECT 1 FROM t_presensi pr
                WHERE pr.niplama = p.nip_lama
                AND DATE(pr.tanggal) = t.date
            ) as has_presensi')
        ])
        ->orderBy('t.date', 'desc')
        ->paginate(10);

        return view('ketua-tim.pengajuan', compact('pengajuan'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'jam_mulai_disetujui'  => 'required',
            'jam_selesai_disetujui' => 'required|after:jam_mulai_disetujui',
            'status'               => 'required|in:approved,rejected',
            'note'                 => 'nullable|string',
        ]);

        DB::table('t_transaksi')->where('id_transaksi', $id)->update([
            'status'                => $request->status,
            'jam_mulai_disetujui'   => $request->jam_mulai_disetujui,
            'jam_selesai_disetujui' => $request->jam_selesai_disetujui,
            'note'                  => $request->note,
            'approved_at'           => now()->toDateString(),
        ]);

        return response()->json(['success' => true]);
    }

    public function presensi($id)
    {
        // Ambil data transaksi dulu untuk dapat NIP dan tanggal
        $transaksi = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->where('t.id_transaksi', $id)
            ->select('t.date', 'p.nip_lama', 'p.nama', 'p.nip')
            ->first();

        if (!$transaksi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Cari presensi berdasarkan niplama dan tanggal
        $presensi = DB::table('t_presensi')
            ->whereDate('tanggal', $transaksi->date)
            ->where('niplama', $transaksi->nip_lama)
            ->first();

        return response()->json([
            'nama'       => $transaksi->nama,
            'nip'        => $transaksi->nip,
            'tanggal'    => \Carbon\Carbon::parse($transaksi->date)->translatedFormat('l, d F Y'),
            'status'     => $presensi->status ?? null,
            'jam_masuk'  => $presensi ? \Carbon\Carbon::parse($presensi->jam_mulai)->format('H:i') : null,
            'jam_pulang' => $presensi ? \Carbon\Carbon::parse($presensi->jam_selesai)->format('H:i') : null,
        ]);
    }

    public function anggotaTim()
    {
        $nipKetua = session('user')['nip'];

        $anggota = DB::table('t_anggota_tim as at')
            ->join('m_pegawai as p', 'at.pegawai_id_pegawai', '=', 'p.id_pegawai')
            ->join('m_tim as mt', 'at.tim_kode_tim', '=', 'mt.kode_tim')
            ->where('mt.nipbaru_ketua', $nipKetua)
            ->select('p.nama', 'p.nip')
            ->get();

        return response()->json($anggota);
    }
}
