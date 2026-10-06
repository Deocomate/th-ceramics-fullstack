<?php

use App\Domains\Archive\Http\ContentArchiveController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:superadmin')->group(function () {
    Route::get('content-archive', [ContentArchiveController::class, 'index'])->name('content-archive.index');
    Route::post('content-archive/export', [ContentArchiveController::class, 'export'])->name('content-archive.export');
    Route::post('content-archive/upload', [ContentArchiveController::class, 'upload'])->name('content-archive.upload');
    Route::post('content-archive/apply', [ContentArchiveController::class, 'apply'])->name('content-archive.apply');
    Route::get('content-archive/download/{name}', [ContentArchiveController::class, 'download'])->name('content-archive.download');
});
