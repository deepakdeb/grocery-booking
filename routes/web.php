<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/orders', 'orders.index')->name('orders');
Route::view('/admin', 'admin.index')->name('admin.index');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
