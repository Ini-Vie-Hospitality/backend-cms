<?php

use App\Http\Controllers\Api\HomepageController;
use Illuminate\Support\Facades\Route;

Route::get('homepage', HomepageController::class)->name('api.homepage');
