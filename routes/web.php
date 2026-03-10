<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

// login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

// page
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard')->middleware('auth');
Route::get('/lembur', function () {return view('lembur');})->name('lembur')->middleware('auth');
Route::get('/akumulasi', function () {return view('akumulasi');})->name('akumulasi')->middleware('auth');
Route::get('/rekapitulasi', function () {return view('rekapitulasi');})->name('rekapitulasi')->middleware('auth');
Route::get('/profile', function () {return view('profile');})->name('profile')->middleware('auth');
Route::get('/presensi', function () {return view('presensi');})->name('presensi')->middleware('auth');

// logout
Route::post('/logout', function () {Auth::logout();return redirect()->route('login');})->name('logout');

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
});
