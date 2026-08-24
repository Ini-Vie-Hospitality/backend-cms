<?php

namespace App\Http\Middleware;

use App\Services\Homepage\HomepageWorkspaceContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveHomepageWorkspace
{
    public function handle(Request $request, Closure $next): Response
    {
        app(HomepageWorkspaceContext::class)->use(
            app(HomepageWorkspaceContext::class)->key(),
        );

        return $next($request);
    }
}
