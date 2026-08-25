<?php

namespace App\Http\Controllers;

use App\Services\Analytics\GoogleAnalyticsService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(GoogleAnalyticsService $analytics): Response
    {
        return Inertia::render('dashboard', [
            'analytics' => $analytics->dashboard(),
        ]);
    }
}
