<?php

use Illuminate\Support\Facades\Route;
use App\Plugins\AccreditationPlugin\AccreditationController;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {
    Route::get('accreditation', [AccreditationController::class, 'index'])->name('accreditation.index');
    Route::get('accreditation/unit/{unitId}', [AccreditationController::class, 'unitReport'])->name('accreditation.report');
    Route::post('accreditation/tag/{biblioId}', [AccreditationController::class, 'tagBiblio'])->name('accreditation.tag');
    Route::delete('accreditation/untag/{biblioId}/{unitId}', [AccreditationController::class, 'untagBiblio'])->name('accreditation.untag');
});
