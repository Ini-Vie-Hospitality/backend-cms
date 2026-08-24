<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\UpdateStoryBlockRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;

class OurStoryController extends SectionController
{
    protected const SECTION = 'our-story';

    public function updateBlock(UpdateStoryBlockRequest $request, int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->updateStoryBlock($request, $item);

        return back();
    }
}
