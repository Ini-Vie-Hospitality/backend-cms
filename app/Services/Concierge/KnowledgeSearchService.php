<?php

namespace App\Services\Concierge;

use App\Models\Concierge\KnowledgeItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Embeddings;

class KnowledgeSearchService
{
    /** @return Collection<int, KnowledgeItem> */
    public function search(string $query): Collection
    {
        if (! KnowledgeItem::query()->searchable()->exists()) {
            return collect();
        }

        $vector = Embeddings::for([$query])
            ->dimensions((int) config('concierge.embedding_dimensions'))
            ->timeout(30)
            ->generate(
                provider: (string) config('concierge.embedding_provider'),
                model: (string) config('concierge.embedding_model'),
            )->first();

        if (DB::getDriverName() !== 'mariadb') {
            return KnowledgeItem::query()->searchable()->limit((int) config('concierge.result_limit'))->get();
        }

        $encoded = json_encode($vector, JSON_THROW_ON_ERROR);
        $maximumDistance = 1 - (float) config('concierge.similarity_threshold');

        return KnowledgeItem::query()->searchable()
            ->select('concierge_knowledge_items.*')
            ->selectRaw('VEC_DISTANCE_COSINE(embedding, VEC_FromText(?)) AS distance', [$encoded])
            ->whereRaw('VEC_DISTANCE_COSINE(embedding, VEC_FromText(?)) <= ?', [$encoded, $maximumDistance])
            ->orderBy('distance')->limit((int) config('concierge.result_limit'))->get();
    }
}
