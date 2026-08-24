<?php

namespace App\Jobs\Concierge;

use App\Models\Concierge\KnowledgeItem;
use App\Services\Concierge\KnowledgeEmbeddingService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportIniVieKnowledgeChunk implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 180;

    public int $tries = 3;

    /**
     * @param  list<array{title: string, category: string, content: string, source_url: string}>  $entries
     */
    public function __construct(public array $entries) {}

    /** @return list<int> */
    public function backoff(): array
    {
        return [10, 60, 180];
    }

    public function handle(KnowledgeEmbeddingService $embeddings): void
    {
        $context = [
            'batch_id' => $this->batch()?->id,
            'job_id' => $this->job?->getJobId(),
            'entry_count' => count($this->entries),
        ];

        if ($this->batch()?->cancelled()) {
            Log::warning('concierge_knowledge_import_cancelled', $context);

            return;
        }

        Log::info('concierge_knowledge_import_started', $context);

        try {
            $embeddedCount = DB::transaction(function () use ($embeddings): int {
                $itemsToEmbed = collect();

                foreach ($this->entries as $entry) {
                    $item = KnowledgeItem::query()->updateOrCreate(
                        ['source_url' => $entry['source_url']],
                        [
                            'title' => $entry['title'],
                            'category' => $entry['category'],
                            'content' => $entry['content'],
                            'status' => 'published',
                        ],
                    );

                    if ($item->wasRecentlyCreated || $item->wasChanged(['title', 'category', 'content', 'status']) || $item->embedding === null) {
                        $itemsToEmbed->push($item);
                    }
                }

                $embeddings->syncMany($itemsToEmbed);

                return $itemsToEmbed->count();
            });

            Log::info('concierge_knowledge_import_completed', [...$context, 'embedded_count' => $embeddedCount]);
        } catch (Throwable $exception) {
            Log::error('concierge_knowledge_import_failed', [...$context, 'exception' => $exception]);

            throw $exception;
        }
    }
}
