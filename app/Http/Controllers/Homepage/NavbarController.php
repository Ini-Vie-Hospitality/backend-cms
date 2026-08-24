<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\SaveNavbarLinkRequest;
use App\Http\Requests\Homepage\UpdateNavbarRequest;
use App\Services\Homepage\HomepagePageService;
use App\Services\Homepage\HomepageRelationService;
use App\Services\Homepage\HomepageSectionService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class NavbarController extends Controller
{
    public function edit(HomepagePageService $pages): Response
    {
        return Inertia::render('homepage/navbar/edit', $pages->navbar());
    }

    public function update(UpdateNavbarRequest $request, HomepageSectionService $sections): RedirectResponse
    {
        $sections->updateNavbar($request);

        return back()->with('success', 'Navbar saved.');
    }

    public function saveLink(SaveNavbarLinkRequest $request, HomepageRelationService $relations, ?int $item = null): RedirectResponse
    {
        $relations->saveNavbarLink($request, $item);

        return back()->with('success', 'Navigation link saved.');
    }

    public function deleteLink(int $item, HomepageRelationService $relations): RedirectResponse
    {
        $relations->delete('homepage_navbar_links', $item);

        return back();
    }
}
