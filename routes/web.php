<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('recipe/{recipe}', [RecipeController::class, 'show'])->name('recipe');

    Route::prefix('account')->group(function () {
        Route::get('profile', [AccountController::class, 'view'])->name('profile');
        Route::post('profile', [AccountController::class, 'store']);
    });
});

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
