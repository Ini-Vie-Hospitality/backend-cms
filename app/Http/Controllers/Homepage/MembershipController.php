<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\SaveMembershipBenefitRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class MembershipController extends SectionController
{
    protected const SECTION = 'membership';

    public function saveBenefit(SaveMembershipBenefitRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveBenefit($request, $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Membership benefit saved.']);

        return back();
    }

    public function deleteBenefit(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_membership_benefits', $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Membership benefit deleted.']);

        return back();
    }
}
