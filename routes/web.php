<?php

use App\Http\Controllers\AdminBusinessIdeaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MicroBusinessRecommendationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publik - tidak perlu login
|--------------------------------------------------------------------------
*/

Route::get('/', [MicroBusinessRecommendationController::class, 'form'])->name('home');

Route::get('/rekomendasi-usaha', [MicroBusinessRecommendationController::class, 'form'])->name('rekomendasi.form');
Route::post('/rekomendasi-usaha', [MicroBusinessRecommendationController::class, 'recommend'])->name('rekomendasi.recommend');
Route::get('/rekomendasi-usaha/{businessIdea:slug}', [MicroBusinessRecommendationController::class, 'show'])->name('rekomendasi.detail');

/*
|--------------------------------------------------------------------------
| Autentikasi (admin)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');

Route::get('/lupa-password', [AuthController::class, 'showForgot'])->name('password.request')->middleware('guest');
Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin - wajib login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [MicroBusinessRecommendationController::class, 'dashboard'])->name('dashboard');

    Route::get('/master/modal-usaha', [AdminBusinessIdeaController::class, 'capitalMaster'])->name('master.capitals.index');
    Route::get('/master/kategori-usaha', [AdminBusinessIdeaController::class, 'categoryMaster'])->name('master.categories.index');
    Route::get('/master/waktu-luang', [AdminBusinessIdeaController::class, 'timeMaster'])->name('master.times.index');
    Route::get('/master/formula', [AdminBusinessIdeaController::class, 'formulaMaster'])->name('master.formula.index');

    Route::post('/master/options', [AdminBusinessIdeaController::class, 'storeMasterOption'])->name('master-options.store');
    Route::put('/master/options/{masterOption}', [AdminBusinessIdeaController::class, 'updateMasterOption'])->name('master-options.update');
    Route::delete('/master/options/{masterOption}', [AdminBusinessIdeaController::class, 'destroyMasterOption'])->name('master-options.destroy');
    Route::put('/master/formula/{formulaSetting}', [AdminBusinessIdeaController::class, 'updateFormulaSetting'])->name('master.formula.update');

    Route::get('/kategori-usaha', [AdminBusinessIdeaController::class, 'categories'])->name('business-categories.index');

    Route::get('/data-usaha', [AdminBusinessIdeaController::class, 'index'])->name('business-ideas.index');
    Route::get('/data-usaha/template', [AdminBusinessIdeaController::class, 'template'])->name('business-ideas.template');
    Route::post('/data-usaha/import', [AdminBusinessIdeaController::class, 'import'])->name('business-ideas.import');
    Route::delete('/data-usaha', [AdminBusinessIdeaController::class, 'destroyAll'])->name('business-ideas.destroy-all');
    Route::put('/data-usaha/{businessIdea}', [AdminBusinessIdeaController::class, 'update'])->name('business-ideas.update');
    Route::delete('/data-usaha/{businessIdea}', [AdminBusinessIdeaController::class, 'destroy'])->name('business-ideas.destroy');
});
