<?php

namespace App\Services\Homepage;

use Illuminate\Support\Facades\Http;

class HomepageRevalidationService
{
    public function revalidate(): void
    {
        $url = config('services.homepage.revalidate_url');
        $secret = config('services.homepage.revalidate_secret');
        if (! is_string($url) || $url === '' || ! is_string($secret) || $secret === '') {
            return;
        }
        Http::timeout(5)->withToken($secret)->post($url);
    }
}
