<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni  = Carbon::now()->month;
        $tahunIni  = Carbon::now()->year;
        $hariIni   = Carbon::today();

        // --- Statistik pengajuan bulan ini ---
        $stats = [
            'total'     => DB::table('t_transaksi')->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->count(),
            'disetujui' => DB::table('t_transaksi')->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'approved')->count(),
            'diproses'  => DB::table('t_transaksi')->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'pending')->count(),
            'ditolak'   => DB::table('t_transaksi')->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'rejected')->count(),
        ];

        // --- Lembur hari ini (semua karyawan) ---
        $lemburHariIni = DB::table('t_transaksi as t')
            ->join('m_pegawai as p', 't.submitted_by_NIP', '=', 'p.nip')
            ->leftJoin('m_tim as tim', 't.tim_kode_tim', '=', 'tim.kode_tim')
            ->whereDate('t.date', $hariIni)
            ->select(
                'p.nama as nama_pegawai',
                'tim.nama_tim',
                't.jam_mulai',
                't.jam_selesai',
                't.jam_mulai_disetujui',
                't.jam_selesai_disetujui',
                't.status'
            )
            ->orderBy('t.submitted_at', 'desc')
            ->get();

        $bulanPeriode = now()->format('Y-m'); 

        $dokumen = DB::table('t_dokumen')
            ->where('periode', $bulanPeriode)
            ->orderBy('generated_at', 'desc')
            ->limit(3)
            ->get();

        // --- Ringkasan bulan ini ---
        $transaksiDisetujui = DB::table('t_transaksi')
            ->whereMonth('date', $bulanIni)
            ->whereYear('date', $tahunIni)
            ->where('status', 'approved')
            ->select('submitted_by_NIP', 'jam_mulai_disetujui', 'jam_selesai_disetujui')
            ->get();

        $totalMenit = $transaksiDisetujui->sum(function ($t) {
            if (!$t->jam_mulai_disetujui || !$t->jam_selesai_disetujui) return 0;
            return Carbon::parse($t->jam_selesai_disetujui)->diffInMinutes(Carbon::parse($t->jam_mulai_disetujui));
        });

        $karyawanAktif = $transaksiDisetujui->pluck('submitted_by_NIP')->unique()->count();
        $totalJam      = round($totalMenit / 60, 1);
        $rataRataJam   = $karyawanAktif > 0 ? round($totalJam / $karyawanAktif, 1) : 0;

        $periodeIni = Carbon::now()->format('Y-m');

        // Cek presensi bulan ini (asumsi tabel t_presensi dengan kolom bulan & tahun)
        $presensiLengkap = DB::table('t_riwayat_presensi')
        ->where('periode', $periodeIni)
        ->exists();

        // Cek SPKL & laporan bulan ini di tabel dokumen
        $spklGenerated = DB::table('t_dokumen')
            ->where('periode', $bulanPeriode)
            ->where('type', 'spkl')
            ->whereNotNull('file_blob')
            ->exists();

        $laporanGenerated = DB::table('t_dokumen')
            ->where('periode', $bulanPeriode)
            ->whereIn('type', ['laporan_pns', 'laporan_pppk'])
            ->whereNotNull('file_blob')
            ->exists();

        $ringkasan = [
            'total_jam'         => $totalJam,
            'karyawan_aktif'    => $karyawanAktif,
            'rata_rata_jam'     => $rataRataJam,
            'presensi_lengkap'  => $presensiLengkap,
            'spkl_generated'    => $spklGenerated,
            'laporan_generated' => $laporanGenerated,
        ];

        // --- Notifikasi otomatis ---
        $notifikasi = collect();

        if (!$presensiLengkap) {
            $notifikasi->push([
                'pesan'  => 'Presensi ' . Carbon::now()->translatedFormat('F Y') . ' belum diinput.',
                'level'  => 'danger',
                'waktu'  => 'Hari ini',
            ]);
        }

        if (!$spklGenerated) {
            $notifikasi->push([
                'pesan'  => 'SPKL ' . Carbon::now()->translatedFormat('F Y') . ' belum di-generate.',
                'level'  => 'warning',
                'waktu'  => 'Batas: ' . Carbon::now()->endOfMonth()->translatedFormat('d M Y'),
            ]);
        }

        if (!$laporanGenerated) {
            $notifikasi->push([
                'pesan'  => 'Laporan lembur ' . Carbon::now()->translatedFormat('F Y') . ' belum di-generate.',
                'level'  => 'warning',
                'waktu'  => 'Batas: ' . Carbon::now()->endOfMonth()->translatedFormat('d M Y'),
            ]);
        }

        return view('admin.dashboard', compact(
            'stats',
            'lemburHariIni',
            'dokumen',
            'ringkasan',
            'notifikasi'
        ));
    }
}
