<?php

use Illuminate\Support\Facades\Route;

// Routes untuk Visitor Counter Plugin
Route::get('/visitor', [\App\Plugins\VisitorPlugin\VisitorController::class, 'index'])->name('opac.visitor');
Route::post('/visitor', [\App\Plugins\VisitorPlugin\VisitorController::class, 'store'])->name('opac.visitor.store');

// Admin Panel Routes for Visitor Plugin (Requires auth)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/visitor-report', [\App\Plugins\VisitorPlugin\VisitorController::class, 'adminIndex'])->name('visitor.index');
    Route::delete('/visitor-report/{id}', [\App\Plugins\VisitorPlugin\VisitorController::class, 'adminDestroy'])->name('visitor.destroy');
});
