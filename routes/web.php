<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TestRecipeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('recipe/{recipe}', [RecipeController::class, 'show'])->name('recipe')->whereNumber('recipe');
    Route::get('/search', [RecipeController::class, 'search'])->name('search');

    Route::prefix('account')->group(function () {
        Route::get('profile', [AccountController::class, 'view'])->name('profile');
        Route::post('profile', [AccountController::class, 'store']);
        Route::post('profile/meteo', [AccountController::class, 'meteo'])->name('profile.store.meteo');
    });
});

// Auth
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store']);
Route::get('logout', [LoginController::class, 'destroy'])->name('logout');
