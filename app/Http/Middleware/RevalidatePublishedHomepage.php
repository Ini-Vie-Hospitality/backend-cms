<?php

namespace App\Http\Middleware;

use App\Services\Homepage\HomepageRevalidationService;
use App\Services\Homepage\HomepageWorkspaceContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RevalidatePublishedHomepage
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (! $request->isMethod('GET') && app(HomepageWorkspaceContext::class)->key() === 'published' && $response->isSuccessful()) {
            app(HomepageRevalidationService::class)->revalidate();
        }

        return $response;
    }
}
