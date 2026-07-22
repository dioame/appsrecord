<?php

use App\Http\Controllers\AppListingController;
use App\Http\Controllers\AppRatingController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/docs', [HomeController::class, 'docs'])->name('docs');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/apps/{slug}', [HomeController::class, 'show'])->name('apps.public');
Route::get('/categories/{slug}', [HomeController::class, 'category'])->name('categories.show');
Route::get('/creators/{slug}', [CreatorController::class, 'show'])->name('creators.show');
Route::get('/creators/{slug}/cv.pdf', [CreatorController::class, 'cvPdf'])->name('creators.cv');
Route::get('/creators/{slug}/{appSlug}', [CreatorController::class, 'app'])->name('creators.app');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/apps/{slug}/rate', [AppRatingController::class, 'store'])->name('apps.rate');

    Route::resource('my-apps', AppListingController::class)
        ->parameters(['my-apps' => 'app'])
        ->names('my-apps');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
