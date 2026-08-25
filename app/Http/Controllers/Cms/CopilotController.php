<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\CopilotGenerateRequest;
use App\Services\Cms\CopilotService;
use Illuminate\Http\JsonResponse;

class CopilotController extends Controller
{
    public function generate(CopilotGenerateRequest $request, CopilotService $copilot): JsonResponse
    {
        return response()->json($copilot->generate($request->validated()));
    }
}
