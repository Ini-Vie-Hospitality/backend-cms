<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\SaveItemRequest;
use App\Http\Requests\Homepage\UpdateSectionRequest;
use App\Services\Homepage\HomepageItemService;
use App\Services\Homepage\HomepagePageService;
use App\Services\Homepage\HomepageSectionService;
use App\Support\HomepageDefinitions;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

abstract class CollectionController extends Controller
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

    public function create(): Response
    {
        return Inertia::render('homepage/'.static::SECTION.'/create');
    }

    public function edit(int $item, HomepagePageService $pages): Response
    {
        return Inertia::render('homepage/'.static::SECTION.'/edit', ['item' => $pages->item(static::SECTION, $item)]);
    }

    public function store(SaveItemRequest $request, HomepageItemService $items): RedirectResponse
    {
        $items->save(static::SECTION, $request);

        return back()->with('success', 'Item saved.');
    }

    public function updateItem(SaveItemRequest $request, int $item, HomepageItemService $items): RedirectResponse
    {
        $items->save(static::SECTION, $request, $item);

        return back()->with('success', 'Item saved.');
    }

    public function destroy(int $item, HomepageItemService $items): RedirectResponse
    {
        $items->delete(static::SECTION, $item);

        return back()->with('success', 'Item deleted.');
    }
}
