<?php

use App\Domains\Media\Http\Admin\StagedImageUploadController;
use Illuminate\Support\Facades\Route;

Route::post('media/staged-images', [StagedImageUploadController::class, 'store'])->name('media.staged-images.store');
