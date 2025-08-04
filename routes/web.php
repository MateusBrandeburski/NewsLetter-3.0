<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\Web\Atletismo\CorridasDFController;
use App\Http\Controllers\Web\Imagens\ImgController;

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

// Vue.js com Inertia.js
Route::get('/', [CorridasDFController::class, 'index'])->name('corridas-df');
Route::get('/corridasDF', [CorridasDFController::class, 'index'])->name('corridas-df');
Route::post('/corridasDF', [CorridasDFController::class, 'index'])->name('corridas-df-post');
Route::get('/corridasDF-inertia', [CorridasDFController::class, 'index'])->name('corridas-df-inertia');

Route::get('/corrida/imagem', [ImgController::class, 'acessaImg'])->name('corrida-imagem');