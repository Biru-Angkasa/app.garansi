<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaransiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;


Route::get('/', function () {
    if (auth()->check()) {
        return app(DashboardController::class)->index();
    }
    return view('welcome');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route Profile Breeze
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Route Garansi
    Route::prefix('garansi')->name('garansi.')->group(function () {
        Route::get('/', [GaransiController::class, 'index'])->name('index');
        Route::get('/create', [GaransiController::class, 'create'])->name('create');
        Route::post('/', [GaransiController::class, 'store'])->name('store');
        Route::get('/{garansi}', [GaransiController::class, 'show'])->name('show');
        Route::get('/{garansi}/edit', [GaransiController::class, 'edit'])->name('edit');
        Route::put('/{garansi}', [GaransiController::class, 'update'])->name('update');
        Route::delete('/{garansi}', [GaransiController::class, 'destroy'])->middleware('role:admin')->name('destroy');
        Route::post('/{garansi}/status', [GaransiController::class, 'updateStatus'])->name('status');
        Route::patch('/{garansi}/items/{item}/replace-sn', [GaransiController::class, 'replaceItemSerial'])->name('items.replace-sn');
        Route::post('/{garansi}/send-wa', [GaransiController::class, 'sendWA'])->name('send-wa');
        Route::post('/scrape-invoice', [GaransiController::class, 'scrapeInvoice'])->name('scrape-invoice');
        Route::post('/{garansi}/resend-wa/{log}', [GaransiController::class, 'resendWA'])->name('resend-wa');
    });

    // Route Manajemen User (Khusus Admin)
    Route::middleware(['role:admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/cek-garansi', [TrackingController::class, 'index'])->name('tracking.index');

require __DIR__.'/auth.php';
