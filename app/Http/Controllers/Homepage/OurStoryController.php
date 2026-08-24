<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\UpdateStoryBlockRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class OurStoryController extends SectionController
{
    protected const SECTION = 'our-story';

    public function updateBlock(UpdateStoryBlockRequest $request, int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->updateStoryBlock($request, $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Story block saved.']);

        return back();
    }
}
