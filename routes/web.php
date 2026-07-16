<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaransiController;
use App\Http\Controllers\GaransiChatController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ========== ROUTE PUBLIC (TIDAK PERLU LOGIN) ==========

// Root "/" - Login Page (jika belum login) atau redirect ke Dashboard (jika sudah login)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Cek Garansi (Public - tanpa login)
Route::get('/cek-garansi', [TrackingController::class, 'index'])->name('tracking.index');

// Chat publik untuk customer di halaman cek garansi (tanpa login)
Route::get('/tracking/{garansi}/chat', [GaransiChatController::class, 'indexPublic'])->name('tracking.chat.index');
Route::post('/tracking/{garansi}/chat', [GaransiChatController::class, 'storeFromCustomer'])->name('tracking.chat.store');


// ========== ROUTE AUTH (PERLU LOGIN) ==========
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Floating chat bubble (list garansi yang punya percakapan aktif)
    Route::get('/chats/active', [GaransiChatController::class, 'active'])->name('garansi.chat.active');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Garansi
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

        // Chat teknisi (perlu login)
        Route::prefix('{garansi}/chat')->name('chat.')->group(function () {
            Route::get('/', [GaransiChatController::class, 'index'])->name('index');
            Route::post('/', [GaransiChatController::class, 'store'])->name('store');
            Route::delete('/', [GaransiChatController::class, 'destroy'])->middleware('role:admin')->name('destroy');
        });
    });

    // Manajemen User (Admin Only)
    Route::middleware(['role:admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';