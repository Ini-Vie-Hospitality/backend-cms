<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\SaveFooterContactRequest;
use App\Http\Requests\Homepage\SaveFooterSocialRequest;
use App\Services\Homepage\HomepageRelationService;
use Illuminate\Http\RedirectResponse;

class FooterController extends SectionController
{
    protected const SECTION = 'footer';

    public function saveContact(SaveFooterContactRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveFooterContact($request, $item);

        return back();
    }

    public function deleteContact(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_footer_contacts', $item);

        return back();
    }

    public function saveSocial(SaveFooterSocialRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveFooterSocial($request, $item);

        return back();
    }

    public function deleteSocial(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_footer_socials', $item);

        return back();
    }
}
