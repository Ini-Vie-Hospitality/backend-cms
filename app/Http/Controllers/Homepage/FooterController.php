<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\SaveFooterContactRequest;
use App\Http\Requests\Homepage\SaveFooterSocialRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class FooterController extends SectionController
{
    protected const SECTION = 'footer';

    public function saveContact(SaveFooterContactRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveFooterContact($request, $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Footer contact saved.']);

        return back();
    }

    public function deleteContact(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_footer_contacts', $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Footer contact deleted.']);

        return back();
    }

    public function saveSocial(SaveFooterSocialRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveFooterSocial($request, $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Footer social link saved.']);

        return back();
    }

    public function deleteSocial(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_footer_socials', $item);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Footer social link deleted.']);

        return back();
    }
}
