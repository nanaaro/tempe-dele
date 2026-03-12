<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LemburController;


Route::get('/', function () {
    return view('welcome');
});

// login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

// page
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard')->middleware('checksession');
Route::get('/lembur', function () {return view('lembur');})->name('lembur')->middleware('checksession');
Route::get('/akumulasi', function () {return view('akumulasi');})->name('akumulasi')->middleware('checksession');
Route::get('/rekapitulasi', function () {return view('rekapitulasi');})->name('rekapitulasi')->middleware('checksession');
Route::get('/profile', function () {return view('profile');})->name('profile')->middleware('checksession');
Route::get('/presensi', function () {return view('presensi');})->name('presensi')->middleware('checksession');

// logout
Route::post('/logout', function () {Auth::logout();return redirect()->route('login');})->name('logout');

// Controller
Route::get('/lembur', [LemburController::class, 'index'])->name('lembur')->middleware('checksession');

// Ketua Tim
Route::prefix('ketua-tim')->name('ketua-tim.')->group(function () {
    Route::get('/dashboard', fn() => view('ketua-tim.dashboard'))->name('dashboard');
    Route::get('/pengajuan', fn() => view('ketua-tim.pengajuan'))->name('pengajuan');
    Route::get('/historis', fn() => view('ketua-tim.historis'))->name('historis');
    Route::get('/spkl', fn() => view('ketua-tim.spkl'))->name('spkl');
    Route::get('/laporan', fn() => view('ketua-tim.laporan'))->name('laporan');
    Route::get('/daftar_hadir', fn() => view('ketua-tim.daftar_hadir'))->name('daftar_hadir');
    Route::get('/akumulasi', fn() => view('ketua-tim.akumulasi'))->name('akumulasi');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/presensi', fn() => view('admin.presensi'))->name('presensi');
    Route::get('/spkl', fn() => view('admin.spkl'))->name('spkl');
    Route::get('/laporan', fn() => view('admin.laporan'))->name('laporan');
    Route::get('/daftar_hadir', fn() => view('admin.daftar_hadir'))->name('daftar_hadir');
    Route::get('/akumulasi', fn() => view('admin.akumulasi'))->name('akumulasi');
});


