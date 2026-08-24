<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(fn () => Route::inertia('dashboard', 'dashboard')->name('dashboard'));

require __DIR__.'/homepage.php';
require __DIR__.'/settings.php';
