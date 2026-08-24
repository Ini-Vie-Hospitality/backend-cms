<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\UpdateBrandIntroductionRequest;
use App\Services\Homepage\BrandIntroductionService;
use App\Services\Homepage\HomepagePageService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BrandIntroductionController extends Controller
{
    public function edit(HomepagePageService $pages): Response
    {
        return Inertia::render('homepage/brand-introduction/edit', $pages->brandIntroduction());
    }

    public function update(UpdateBrandIntroductionRequest $request, BrandIntroductionService $service): RedirectResponse
    {
        $service->update($request);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Brand introduction saved.']);

        return back();
    }
}
