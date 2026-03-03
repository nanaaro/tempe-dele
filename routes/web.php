<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// login
Route::get('/login', function () {return view('auth');})->name('login');

// page
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
Route::get('/lembur', function () {return view('lembur');})->name('lembur');
Route::get('/akumulasi', function () {return view('akumulasi');})->name('akumulasi');
Route::get('/rekapitulasi', function () {return view('rekapitulasi');})->name('rekapitulasi');
Route::get('/profile', function () {return view('profile');})->name('profile');
Route::get('/presensi', function () {return view('presensi');})->name('presensi');

// logout
Route::post('/logout', function () {Auth::logout();return redirect()->route('login');})->name('logout');
