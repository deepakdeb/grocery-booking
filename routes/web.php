<?php

use App\Http\Controllers\Admin\InventoryPageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\User\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::get('/orders', [StorefrontController::class, 'index'])->name('orders');

Route::get('/admin', [InventoryPageController::class, 'dashboard'])->name('admin.index');
Route::get('/admin/items', [InventoryPageController::class, 'index'])->name('admin.items.index');
Route::get('/admin/items/create', [InventoryPageController::class, 'create'])->name('admin.items.create');
Route::get('/admin/items/{id}/edit', [InventoryPageController::class, 'edit'])->name('admin.items.edit');

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
