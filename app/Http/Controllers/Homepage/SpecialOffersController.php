<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\UpdateSpecialOfferRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;

class SpecialOffersController extends SectionController
{
    protected const SECTION = 'special-offers';

    public function updateOffer(UpdateSpecialOfferRequest $request, int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->updateOffer($request, $item);

        return back();
    }
}
