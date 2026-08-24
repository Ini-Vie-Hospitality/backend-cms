<?php

namespace App\Services\Concierge;

use App\Models\Concierge\KnowledgeItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Embeddings;
use RuntimeException;

class KnowledgeEmbeddingService
{
    public function sync(KnowledgeItem $item): void
    {
        if ($item->status !== 'published') {
            $item->newQuery()->whereKey($item)->update(['embedding' => null]);

            return;
        }

        $this->syncMany([$item]);
    }

    /** @param iterable<KnowledgeItem> $items */
    public function syncMany(iterable $items): void
    {
        /** @var Collection<int, KnowledgeItem> $items */
        $items = collect($items)
            ->filter(fn (KnowledgeItem $item): bool => $item->status === 'published')
            ->values();

        if ($items->isEmpty()) {
            return;
        }

        $vectors = Embeddings::for($items->map(fn (KnowledgeItem $item): string => $this->document($item))->all())
            ->dimensions((int) config('concierge.embedding_dimensions'))
            ->timeout(30)
            ->generate(
                provider: (string) config('concierge.embedding_provider'),
                model: (string) config('concierge.embedding_model'),
            );

        if ($vectors->count() !== $items->count()) {
            throw new RuntimeException('Embedding provider returned an unexpected vector count.');
        }

        foreach ($items as $index => $item) {
            $this->store($item, $vectors->embeddings[$index]);
        }
    }

    /** @param array<float> $vector */
    private function store(KnowledgeItem $item, array $vector): void
    {
        $encoded = json_encode($vector, JSON_THROW_ON_ERROR);

        if (DB::getDriverName() === 'mariadb') {
            DB::update('UPDATE concierge_knowledge_items SET embedding = VEC_FromText(?), updated_at = ? WHERE id = ?', [$encoded, now(), $item->getKey()]);
        } else {
            $item->newQuery()->whereKey($item)->update(['embedding' => $encoded]);
        }

        $item->refresh();
    }

    private function document(KnowledgeItem $item): string
    {
        return "Title: {$item->title}\nCategory: {$item->category}\nContent: {$item->content}";
    }
}
