<?php

use App\Http\Controllers\Admin\GroceryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin/grocery-items', [GroceryController::class, 'index']);
    Route::post('/admin/grocery-items', [GroceryController::class, 'store']);
    Route::get('/admin/grocery-items/{id}', [GroceryController::class, 'show']);
    Route::put('/admin/grocery-items/{id}', [GroceryController::class, 'update']);
    Route::delete('/admin/grocery-items/{id}', [GroceryController::class, 'destroy']);
});

Route::middleware(['auth:api', 'role:user,admin'])->group(function () {
    Route::get('/items', [GroceryController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
});
