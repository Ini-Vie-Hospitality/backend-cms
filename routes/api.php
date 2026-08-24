<?php

use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\HomepagePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('homepage', HomepageController::class)->name('api.homepage');
Route::get('homepage/preview', HomepagePreviewController::class)->name('api.homepage.preview');
