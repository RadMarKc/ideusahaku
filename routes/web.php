<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MicroBusinessRecommendationController;

Route::get('/', function () {
    return redirect()->route('rekomendasi.form');
});

Route::get('/rekomendasi-usaha', [MicroBusinessRecommendationController::class, 'form'])->name('rekomendasi.form');
Route::post('/rekomendasi-usaha', [MicroBusinessRecommendationController::class, 'recommend'])->name('rekomendasi.recommend');
