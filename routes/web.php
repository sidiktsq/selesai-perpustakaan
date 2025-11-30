<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Authentication Routes
Auth::routes();

// Redirect root to dashboard
Route::redirect('/', '/dashboards');

// Dashboard Routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::resource('dashboards', DashboardController::class);
    Route::patch('dashboards/{dashboard}/toggle-status', [DashboardController::class, 'toggleStatus'])
        ->name('dashboards.toggle-status');
});

// Other resource routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::resource('buku', App\Http\Controllers\BukuController::class);
    Route::resource('kategori', App\Http\Controllers\KategoriController::class);
    Route::resource('pengarang', App\Http\Controllers\PengarangController::class);
    Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);
});

// Home route (protected by auth)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');