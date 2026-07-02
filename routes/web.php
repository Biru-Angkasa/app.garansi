<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaransiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('garansi')->name('garansi.')->group(function () {
    Route::get('/', [GaransiController::class, 'index'])->name('index');
    Route::get('/create', [GaransiController::class, 'create'])->name('create');
    Route::post('/', [GaransiController::class, 'store'])->name('store');
    Route::get('/{garansi}', [GaransiController::class, 'show'])->name('show');
    Route::get('/{garansi}/edit', [GaransiController::class, 'edit'])->name('edit');
    Route::put('/{garansi}', [GaransiController::class, 'update'])->name('update');
    Route::delete('/{garansi}', [GaransiController::class, 'destroy'])->name('destroy');

    // Update status (AJAX)
    Route::post('/{garansi}/status', [GaransiController::class, 'updateStatus'])->name('status');

    // Kirim WA manual
    Route::post('/{garansi}/send-wa', [GaransiController::class, 'sendWA'])->name('send-wa');

    // Scraping SO (placeholder - logic nanti)
    Route::post('/scrape-so', [GaransiController::class, 'scrapeSO'])->name('scrape-so');
});