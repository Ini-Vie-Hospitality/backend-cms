<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\UpdatePopupRequest;
use App\Services\Homepage\HomepagePageService;
use App\Services\Homepage\PopupService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PopupController extends Controller
{
    public function edit(HomepagePageService $pages): Response
    {
        return Inertia::render('homepage/popup/edit', $pages->popup());
    }

    public function update(UpdatePopupRequest $request, PopupService $service): RedirectResponse
    {
        $service->update($request);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Popup saved.']);

        return back();
    }
}
