<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\SaveMembershipBenefitRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;

class MembershipController extends SectionController
{
    protected const SECTION = 'membership';

    public function saveBenefit(SaveMembershipBenefitRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveBenefit($request, $item);

        return back();
    }

    public function deleteBenefit(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_membership_benefits', $item);

        return back();
    }
}
