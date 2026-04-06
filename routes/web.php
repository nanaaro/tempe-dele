<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\admin\TimController;
use App\Http\Controllers\admin\AnggotaTimController;
use App\Http\Controllers\admin\PenggunaController;
use App\Http\Controllers\admin\RateController;
use App\Http\Controllers\admin\DokumenController;
use App\Http\Controllers\admin\PresensiUploadController;

use App\Http\Controllers\LemburController;
use App\Http\Controllers\admin\DokumenGenerateController;

// ─── Public ───────────────────────────────────────────────
Route::get('/', fn() => view('welcome'));

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::post('/logout', function () {session()->flush(); return redirect()->route('login'); })->name('logout');


Route::get('/lembur', [LemburController::class, 'index'])->name('lembur')->middleware('checksession');
Route::post('/lembur', [LemburController::class, 'store'])->name('lembur.store')->middleware('checksession');
Route::get('/lembur/tim', [LemburController::class, 'timPegawai'])->name('lembur.tim')->middleware('checksession');

// ─── Authenticated (semua role) ───────────────────────────
Route::middleware('checksession')->group(function () {

    Route::get('/dashboard',     fn() => view('dashboard'))->name('dashboard');
    Route::get('/akumulasi',     fn() => view('akumulasi'))->name('akumulasi');
    Route::get('/rekapitulasi', [\App\Http\Controllers\pegawai\RekapitulasiController::class, 'index'])->name('rekapitulasi')->middleware('checksession');
    Route::get('/profile', function () { $nip = session('user')['nip']; $user = \DB::table('m_pegawai')->where('nip', $nip)->first(); return view('profile', compact('user'));})->name('profile')->middleware('checksession');

    // ─── Pegawai ──────────────────────────────────────────
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
});

    // ─── Ketua Tim ────────────────────────────────────────
    Route::prefix('ketua-tim')->name('ketua-tim.')->group(function () {
        Route::get('/dashboard',    fn() => view('ketua-tim.dashboard'))->name('dashboard');
        Route::get('/pengajuan',    fn() => view('ketua-tim.pengajuan'))->name('pengajuan');
        Route::get('/lembur', [LemburController::class, 'index'])->name('lembur')->middleware('checksession');
        Route::post('/lembur', [LemburController::class, 'store'])->name('lembur.store')->middleware('checksession');
        Route::get('/pengajuan', [\App\Http\Controllers\ketuatim\PengajuanController::class, 'index'])->name('pengajuan')->middleware('checksession');
        Route::post('/pengajuan/{id}/approve', [\App\Http\Controllers\ketuatim\PengajuanController::class, 'approve'])->name('pengajuan.approve')->middleware('checksession');
        Route::get('/pengajuan/{id}/presensi', [\App\Http\Controllers\ketuatim\PengajuanController::class, 'presensi'])->name('pengajuan.presensi')->middleware('checksession');
        Route::get('/pengajuan/anggota', [\App\Http\Controllers\ketuatim\PengajuanController::class, 'anggotaTim'])->name('pengajuan.anggota')->middleware('checksession');
        Route::get('/dashboard', [\App\Http\Controllers\ketuatim\DashboardController::class, 'index'])->name('dashboard')->middleware('checksession');
    });

    // ─── Admin ────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('/pegawai/all', [PresensiController::class, 'getAllPegawai'])->name('pegawai.all')->middleware('checksession');

        //SPKL
        Route::get('/spkl', [\App\Http\Controllers\admin\RekapitulasiController::class, 'index'])->name('spkl');
        Route::get('/rekapitulasi', [\App\Http\Controllers\admin\RekapitulasiController::class, 'index'])->name('rekapitulasi');
        Route::get('/rekapitulasi', [\App\Http\Controllers\pegawai\RekapitulasiController::class, 'index'])->name('rekapitulasi')->middleware('checksession');
        Route::get('/dokumen/download/{type}', [\App\Http\Controllers\admin\DokumenGenerateController::class, 'download'])->name('dokumen.download')->middleware('checksession');

        //Laporan
        Route::get('/laporan', [\App\Http\Controllers\admin\LaporanController::class, 'index'])->name('laporan');

        //Akumulasi
        Route::get('/akumulasi', [\App\Http\Controllers\admin\AkumulasiController::class, 'index'])->name('akumulasi');
        Route::get('/akumulasi/download', [\App\Http\Controllers\admin\AkumulasiController::class, 'download'])->name('akumulasi.download')->middleware('checksession');

        //Daftar Hadir
        Route::get('/daftar-hadir', [\App\Http\Controllers\admin\DaftarHadirController::class, 'index'])->name('daftar_hadir');
        Route::get('/daftar-hadir/download', [\App\Http\Controllers\admin\DaftarHadirController::class, 'download'])->name('daftar_hadir.download')->middleware('checksession');

        // Presensi
        Route::get('/presensi', [\App\Http\Controllers\admin\PresensiController::class, 'index'])->name('presensi');
        Route::get('/presensi',           [PresensiUploadController::class, 'index'])->name('presensi');
        Route::post('/presensi/upload',   [PresensiUploadController::class, 'upload'])->name('presensi.upload');
        Route::get('/presensi/riwayat',   [PresensiUploadController::class, 'riwayat'])->name('presensi.riwayat');
        Route::get('/presensi/kalender',  [PresensiUploadController::class, 'getKalender'])->name('presensi.kalender');
        Route::get('/presensi/tim',       [PresensiController::class, 'getTim'])->name('presensi.tim');
        Route::get('/presensi/pegawai',   [PresensiController::class, 'getPegawai'])->name('presensi.pegawai');
        Route::get('/riwayat_presensi', [\App\Http\Controllers\admin\PresensiUploadController::class, 'index'])->name('riwayat_presensi');

        // Tim
        Route::get('/tim', [\App\Http\Controllers\admin\TimController::class, 'index'])->name('tim');
        Route::get('/tim',                [TimController::class, 'index'])->name('tim');
        Route::post('/tim',               [TimController::class, 'store'])->name('tim.store');
        Route::put('/tim/{kode_tim}',     [TimController::class, 'update'])->name('tim.update');
        Route::delete('/tim/{kode_tim}',  [TimController::class, 'destroy'])->name('tim.destroy');

        // Anggota Tim
        Route::get('/tim/{kode_tim}/anggota',           [AnggotaTimController::class, 'index'])->name('tim.anggota');
        Route::post('/tim/{kode_tim}/anggota',          [AnggotaTimController::class, 'store'])->name('tim.anggota.store');
        Route::delete('/tim/{kode_tim}/anggota',        [AnggotaTimController::class, 'destroy'])->name('tim.anggota.destroy');
        Route::get('/tim/{kode_tim}/pegawai-tersedia',  [AnggotaTimController::class, 'getPegawaiTersedia'])->name('tim.pegawai.tersedia');

        // Pengguna
        Route::get('/pengguna',                    [PenggunaController::class, 'index'])->name('pengguna');
        Route::get('/pengguna/all',                [PenggunaController::class, 'getAll'])->name('pengguna.all');
        Route::post('/pengguna',                   [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::put('/pengguna/{id}',               [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::put('/pengguna/{id}/password',      [PenggunaController::class, 'updatePassword'])->name('pengguna.password');
        Route::delete('/pengguna/{id}',            [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

        // Tarif
        Route::get('/tarif',               [RateController::class, 'index'])->name('tarif');
        Route::put('/tarif/{id_rate}',     [RateController::class, 'update'])->name('tarif.update');

        // Dokumen
        Route::get('/dokumen', [\App\Http\Controllers\admin\DokumenViewController::class, 'index'])->name('dokumen');
        Route::get('/dokumen/generate/spkl',[\App\Http\Controllers\admin\DokumenGenerateController::class, 'spkl'])->name('dokumen.generate.spkl')->middleware('checksession');
        Route::delete('/dokumen/hapus', [\App\Http\Controllers\admin\DokumenViewController::class, 'hapus'])->name('dokumen.hapus')->middleware('checksession');
        Route::get('/dokumen/generate/laporan/{jenis}', [\App\Http\Controllers\admin\DokumenGenerateController::class, 'laporan'])->name('dokumen.generate.laporan')->middleware('checksession');
        Route::get('/dokumen/generate', [\App\Http\Controllers\admin\DokumenViewController::class, 'index'])->name('dokumen.generate')->middleware('checksession');
        Route::get('/dokumen/view/{id}', [\App\Http\Controllers\admin\DokumenGenerateController::class, 'view'])->name('dokumen.view')->middleware('checksession');
        Route::get('/dokumen/view/{id}',        [\App\Http\Controllers\admin\DokumenViewController::class, 'view'])->name('dokumen.view')->middleware('checksession');
        Route::delete('/dokumen/hapus/{id}',    [\App\Http\Controllers\admin\DokumenViewController::class, 'hapusSatu'])->name('dokumen.hapus-satu')->middleware('checksession');

        //Pejabat
        Route::get('/pejabat',           [\App\Http\Controllers\admin\PejabatController::class, 'index'])->name('pejabat');
        Route::post('/pejabat',          [\App\Http\Controllers\admin\PejabatController::class, 'store'])->name('pejabat.store');
        Route::get('/pejabat/{id}/data', [\App\Http\Controllers\admin\PejabatController::class, 'getData'])->name('pejabat.data');
        Route::put('/pejabat/{id}',      [\App\Http\Controllers\admin\PejabatController::class, 'update'])->name('pejabat.update');
        Route::delete('/pejabat/{id}', [\App\Http\Controllers\admin\PejabatController::class, 'destroy'])->name('pejabat.destroy');
    });
});
