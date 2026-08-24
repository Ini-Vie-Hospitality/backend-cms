<?php

use App\Http\Controllers\Api\ConciergeChatController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\HomepagePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('homepage', HomepageController::class)->name('api.homepage');
Route::get('homepage/preview', HomepagePreviewController::class)->name('api.homepage.preview');
Route::post('concierge/chat', ConciergeChatController::class)->middleware('throttle:20,1')->name('api.concierge.chat');
