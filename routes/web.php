<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminBusinessIdeaController;
use App\Http\Controllers\MicroBusinessRecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('rekomendasi.form')
        : app(AuthController::class)->showLogin();
})->name('login');

Route::get('/login', fn () => redirect()->route('login'))->middleware('guest');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/rekomendasi-usaha', [MicroBusinessRecommendationController::class, 'form'])->name('rekomendasi.form');
    Route::post('/rekomendasi-usaha', [MicroBusinessRecommendationController::class, 'recommend'])->name('rekomendasi.recommend');
    Route::get('/rekomendasi-usaha/{businessIdea:slug}', [MicroBusinessRecommendationController::class, 'show'])->name('rekomendasi.detail');

    Route::get('/admin/data-usaha', [AdminBusinessIdeaController::class, 'index'])->name('admin.business-ideas.index');
    Route::get('/admin/data-usaha/template', [AdminBusinessIdeaController::class, 'template'])->name('admin.business-ideas.template');
    Route::post('/admin/data-usaha/import', [AdminBusinessIdeaController::class, 'import'])->name('admin.business-ideas.import');
    Route::put('/admin/data-usaha/{businessIdea}', [AdminBusinessIdeaController::class, 'update'])->name('admin.business-ideas.update');
});
