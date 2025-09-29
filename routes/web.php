<?php

use Illuminate\Support\Facades\Route;
use Iquesters\Product\Http\Controllers\ProductController;

Route::middleware('web')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::prefix('product')->name('products.')->group(function () {

            // Organisation-aware routes (optional)
            Route::get('/organisation/{organisationUid?}', [ProductController::class, 'index'])->name('index');
            Route::get('/organisation/{organisationUid?}/create', [ProductController::class, 'create'])->name('create');
            Route::post('/organisation/{organisationUid?}/store', [ProductController::class, 'store'])->name('store');

            // Product-specific routes (organisation optional)
            Route::get('/{productUid}/show/{organisationUid?}', [ProductController::class, 'show'])->name('show');
            Route::get('/{productUid}/edit/{organisationUid?}', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{productUid}/{organisationUid?}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{productUid}/{organisationUid?}', [ProductController::class, 'destroy'])->name('destroy');
        });
    });
});