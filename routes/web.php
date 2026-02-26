<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.pages.home');
})->name('home');

Route::get('/pricing', function () {
    return view('landing.pages.pricing');
})->name('pricing');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

