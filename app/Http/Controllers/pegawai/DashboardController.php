<?php

namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $nip       = session('user')['nip'];
        $bulanIni  = Carbon::now()->month;
        $tahunIni  = Carbon::now()->year;
        $hariIni   = Carbon::today();
        $sekarang  = Carbon::now();

        // --- Statistik pengajuan bulan ini milik pegawai ---
        $stats = [
            'total'     => DB::table('t_transaksi')->where('submitted_by_NIP', $nip)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->count(),
            'disetujui' => DB::table('t_transaksi')->where('submitted_by_NIP', $nip)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'approved')->count(),
            'diproses'  => DB::table('t_transaksi')->where('submitted_by_NIP', $nip)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'pending')->count(),
            'ditolak'   => DB::table('t_transaksi')->where('submitted_by_NIP', $nip)->whereMonth('date', $bulanIni)->whereYear('date', $tahunIni)->where('status', 'rejected')->count(),
        ];

        // --- Pengajuan terbaru (5 terakhir) ---
        $pengajuanTerbaru = DB::table('t_transaksi')
            ->where('submitted_by_NIP', $nip)
            ->orderBy('submitted_at', 'desc')
            ->limit(3)
            ->get();

        // --- Jadwal lembur yang akan datang ---
        $jadwalMendatang = DB::table('t_transaksi')
            ->where('submitted_by_NIP', $nip)
            ->where('status', 'approved')
            ->whereDate('date', '>', $hariIni)
            ->orderBy('date', 'asc')
            ->limit(3)
            ->get();

        // --- Notifikasi pribadi ---
        $notifikasi = collect();

        // Ada pengajuan yang ditolak bulan ini
        $ditolakBulanIni = DB::table('t_transaksi')
            ->where('submitted_by_NIP', $nip)
            ->whereMonth('date', $bulanIni)
            ->whereYear('date', $tahunIni)
            ->where('status', 'rejected')
            ->count();

        if ($ditolakBulanIni > 0) {
            $notifikasi->push([
                'pesan' => "{$ditolakBulanIni} pengajuan lembur kamu ditolak bulan ini.",
                'level' => 'danger',
                'waktu' => Carbon::now()->translatedFormat('F Y'),
            ]);
        }

        // Ada pengajuan pending
        $pendingCount = DB::table('t_transaksi')
            ->where('submitted_by_NIP', $nip)
            ->where('status', 'pending')
            ->count();

        if ($pendingCount > 0) {
            $notifikasi->push([
                'pesan' => "{$pendingCount} pengajuan lembur masih menunggu persetujuan.",
                'level' => 'warning',
                'waktu' => 'Menunggu review atasan',
            ]);
        }

        // Ada jadwal lembur besok
        $lemburBesok = DB::table('t_transaksi')
            ->where('submitted_by_NIP', $nip)
            ->where('status', 'approved')
            ->whereDate('date', $hariIni->copy()->addDay())
            ->first();

        if ($lemburBesok) {
            $notifikasi->push([
                'pesan' => 'Kamu memiliki jadwal lembur besok, ' . Carbon::parse($lemburBesok->date)->translatedFormat('d F Y') . '.',
                'level' => 'info',
                'waktu' => $lemburBesok->jam_mulai_disetujui
                    ? substr($lemburBesok->jam_mulai_disetujui, 0, 5) . ' - ' . substr($lemburBesok->jam_selesai_disetujui, 0, 5)
                    : '-',
            ]);
        }

        return view('dashboard', compact(
            'stats',
            'pengajuanTerbaru',
            'jadwalMendatang',
            'notifikasi'
        ));
    }
}
