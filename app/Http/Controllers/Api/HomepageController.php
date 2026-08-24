<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Homepage\HomepageResource;
use App\Services\HomepageContentService;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    public function __invoke(HomepageContentService $content): JsonResponse
    {
        return response()->json((new HomepageResource($content->published()))->resolve());
    }
}
