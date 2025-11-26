<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('template', function () {
    return view('layouts.dahsboard');
});

Route::resource('buku', App\Http\Controllers\BukuController::class);
Route::resource('kategori', App\Http\Controllers\KategoriController::class);
Route::resource('pengarang', App\Http\Controllers\PengarangController::class);
Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);