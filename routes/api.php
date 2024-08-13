<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\TugasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['api', 'check.token', 'role:manajer'])->group(function () {
    Route::resource('/pengguna',PenggunaController::class);
});

Route::middleware(['api','check.token'])->group(function () {
    Route::resource('/proyek',ProyekController::class);
    Route::resource('/tugas',TugasController::class);

    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
});