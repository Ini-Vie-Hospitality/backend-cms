<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Homepage\HomepageResource;
use App\Services\Homepage\HomepageWorkspaceContext;
use App\Services\Homepage\HomepageWorkspaceService;
use App\Services\HomepageContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomepagePreviewController extends Controller
{
    public function __invoke(Request $request, HomepageContentService $content, HomepageWorkspaceContext $workspace, HomepageWorkspaceService $workspaces): JsonResponse
    {
        $secret = (string) config('services.homepage.preview_secret');
        abort_unless($secret !== '' && hash_equals($secret, (string) $request->bearerToken()), 403);
        $workspaces->ensureDraft();
        $workspace->use('draft');

        return response()->json((new HomepageResource($content->published()))->resolve())
            ->header('Cache-Control', 'private, no-store');
    }
}
