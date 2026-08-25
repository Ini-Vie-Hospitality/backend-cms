<?php

namespace App\Http\Controllers\Concierge;

use App\Http\Controllers\Controller;
use App\Http\Requests\Concierge\KnowledgeItemRequest;
use App\Models\Concierge\KnowledgeItem;
use App\Services\Concierge\KnowledgeEmbeddingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class KnowledgeItemController extends Controller
{
    public function index(Request $request): Response
    {
        $perPage = (string) $request->query('per_page', '10');
        if (! in_array($perPage, ['10', '50', '100', 'all'], true)) {
            $perPage = '10';
        }

        $query = KnowledgeItem::query()
            ->select(['id', 'title', 'category', 'status', 'updated_at'])
            ->selectRaw('embedding IS NOT NULL as embedding_ready')
            ->latest();
        $transform = fn (KnowledgeItem $item): array => [
            ...$item->toArray(),
            'embedding_ready' => (bool) $item->getAttribute('embedding_ready'),
        ];

        if ($perPage === 'all') {
            $allItems = $query->get()->map($transform)->values();

            return Inertia::render('concierge/knowledge/index', [
                'items' => [
                    'data' => $allItems,
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 'all',
                    'total' => $allItems->count(),
                    'from' => $allItems->isEmpty() ? null : 1,
                    'to' => $allItems->count() ?: null,
                ],
            ]);
        }

        $items = $query->paginate((int) $perPage)->withQueryString();
        $items->through($transform);

        return Inertia::render('concierge/knowledge/index', [
            'items' => $items,
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
