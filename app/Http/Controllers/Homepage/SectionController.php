<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\UpdateSectionRequest;
use App\Services\Homepage\HomepagePageService;
use App\Services\Homepage\HomepageSectionService;
use App\Support\HomepageDefinitions;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

abstract class SectionController extends Controller
{
    protected const SECTION = '';

    public function index(HomepagePageService $pages): Response
    {
        $definition = HomepageDefinitions::section(static::SECTION);

        return Inertia::render('homepage/'.$definition['component'], $pages->section(static::SECTION));
    }

    public function update(UpdateSectionRequest $request, HomepageSectionService $sections): RedirectResponse
    {
        $sections->update(static::SECTION, $request);

        return back()->with('success', 'Section saved.');
    }
}
