<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HomepageContentService;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    public function __invoke(HomepageContentService $content): JsonResponse
    {
        return response()->json($content->published());
    }
}
