<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (request()->user()->role) {
            'admin' => redirect()->route('admin'),
            'kasir' => redirect()->route('kasir'),
            default => abort(403, 'Role akun tidak dikenali.'),
        };
    })->name('dashboard');

    Route::get('/admin', function () {
        return view('admin');
    })->middleware('cek.role:admin')->name('admin');

    Route::get('/kasir', function () {
        return view('kasir');
    })->middleware('cek.role:kasir')->name('kasir');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});