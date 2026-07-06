<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaransiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Profile Breeze
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('garansi')->name('garansi.')->group(function () {
        Route::get('/', [GaransiController::class, 'index'])->name('index');
        Route::get('/create', [GaransiController::class, 'create'])->name('create');
        Route::post('/', [GaransiController::class, 'store'])->name('store');
        Route::get('/{garansi}', [GaransiController::class, 'show'])->name('show');
        Route::get('/{garansi}/edit', [GaransiController::class, 'edit'])->name('edit');
        Route::put('/{garansi}', [GaransiController::class, 'update'])->name('update');
        Route::post('/{garansi}/resend-wa/{log}', [GaransiController::class, 'resendWA'])->name('resend-wa');
        
        // Khusus Admin bisa hapus
        Route::delete('/{garansi}', [GaransiController::class, 'destroy'])
            ->middleware('role:admin')->name('destroy');
            
        Route::post('/{garansi}/status', [GaransiController::class, 'updateStatus'])->name('status');
        Route::post('/{garansi}/send-wa', [GaransiController::class, 'sendWA'])->name('send-wa');
        Route::post('/scrape-invoice', [GaransiController::class, 'scrapeInvoice'])->name('scrape-invoice');
    });
});

require __DIR__.'/auth.php';