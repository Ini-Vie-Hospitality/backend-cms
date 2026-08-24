<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Concierge\ChatRequest;
use App\Services\Concierge\ConciergeService;
use Illuminate\Http\JsonResponse;

class ConciergeChatController extends Controller
{
    public function __invoke(ChatRequest $request, ConciergeService $concierge): JsonResponse
    {
        $data = $request->validated();

        return response()->json($concierge->answer($data['message'], $data['history'] ?? []));
    }
}
