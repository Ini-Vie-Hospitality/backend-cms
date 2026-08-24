<?php

namespace App\Http\Controllers\Concierge;

use App\Http\Controllers\Controller;
use App\Http\Requests\Concierge\KnowledgeItemRequest;
use App\Models\Concierge\KnowledgeItem;
use App\Services\Concierge\KnowledgeEmbeddingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class KnowledgeItemController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('concierge/knowledge/index', [
            'items' => KnowledgeItem::query()->select(['id', 'title', 'category', 'status', 'updated_at'])->selectRaw('embedding IS NOT NULL as embedding_ready')->latest()->get()->map(fn (KnowledgeItem $item) => [
                ...$item->toArray(),
                'embedding_ready' => (bool) $item->getAttribute('embedding_ready'),
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('concierge/knowledge/create');
    }

    public function store(KnowledgeItemRequest $request, KnowledgeEmbeddingService $embeddings): RedirectResponse
    {
        $item = KnowledgeItem::query()->create($request->validated());
        $embeddings->sync($item);

        return to_route('concierge.knowledge.index')->with('toast', ['type' => 'success', 'message' => 'Knowledge saved and indexed.']);
    }

    public function edit(KnowledgeItem $knowledge): Response
    {
        return Inertia::render('concierge/knowledge/edit', ['item' => $knowledge]);
    }

    public function update(KnowledgeItemRequest $request, KnowledgeItem $knowledge, KnowledgeEmbeddingService $embeddings): RedirectResponse
    {
        $knowledge->update($request->validated());
        $embeddings->sync($knowledge);

        return to_route('concierge.knowledge.index')->with('toast', ['type' => 'success', 'message' => 'Knowledge updated and indexed.']);
    }

    public function destroy(KnowledgeItem $knowledge): RedirectResponse
    {
        $knowledge->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Knowledge deleted.']);
    }

    public function reindex(KnowledgeItem $knowledge, KnowledgeEmbeddingService $embeddings): RedirectResponse
    {
        $embeddings->sync($knowledge);

        return back()->with('toast', ['type' => 'success', 'message' => 'Knowledge reindexed.']);
    }

    public function reindexAll(KnowledgeEmbeddingService $embeddings): RedirectResponse
    {
        KnowledgeItem::query()->where('status', 'published')->each(fn (KnowledgeItem $item) => $embeddings->sync($item));

        return back()->with('toast', ['type' => 'success', 'message' => 'Published knowledge reindexed.']);
    }
}
