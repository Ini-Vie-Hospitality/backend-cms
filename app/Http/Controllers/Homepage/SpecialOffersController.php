<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\UpdateSpecialOfferRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class SpecialOffersController extends SectionController
{
    protected const SECTION = 'special-offers';

    public function updateOffer(UpdateSpecialOfferRequest $request, int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->updateOffer($request, $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Special offer saved.']);

        return back();
    }
}
