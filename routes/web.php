<?php

use App\Http\Controllers\Cms\CopilotController;
use App\Http\Controllers\Concierge\KnowledgeItemController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->get('dashboard', DashboardController::class)->name('dashboard');

Route::middleware(['auth', 'verified'])->post('cms/copilot/generate', [CopilotController::class, 'generate'])->name('copilot.generate')->middleware('throttle:12,1');

Route::middleware(['auth', 'verified'])->prefix('cms/concierge')->name('concierge.')->group(function (): void {
    Route::post('knowledge/reindex-all', [KnowledgeItemController::class, 'reindexAll'])->name('knowledge.reindex-all');
    Route::post('knowledge/{knowledge}/reindex', [KnowledgeItemController::class, 'reindex'])->name('knowledge.reindex');
    Route::resource('knowledge', KnowledgeItemController::class)->except('show');
});

require __DIR__.'/homepage.php';
require __DIR__.'/settings.php';
