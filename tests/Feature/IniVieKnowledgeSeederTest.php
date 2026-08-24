<?php

use App\Jobs\Concierge\ImportIniVieKnowledgeChunk;
use App\Models\Concierge\KnowledgeItem;
use App\Services\Concierge\KnowledgeEmbeddingService;
use Database\Seeders\IniVieKnowledgeSeeder;
use Illuminate\Bus\PendingBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Prompts\EmbeddingsPrompt;
use RuntimeException;

uses(RefreshDatabase::class);

/** @return list<array{title: string, category: string, content: string, source_url: string}> */
function inivieKnowledgeFixture(): array
{
    $contents = file_get_contents(database_path('data/inivie-knowledge.json'));
    expect($contents)->not->toBeFalse();

    return json_decode((string) $contents, true, flags: JSON_THROW_ON_ERROR);
}

test('inivie knowledge fixture contains unique public source content', function () {
    $entries = inivieKnowledgeFixture();

    expect($entries)->not->toBeEmpty()
        ->and(collect($entries)->pluck('source_url')->unique())->toHaveCount(count($entries));

    foreach ($entries as $entry) {
        expect($entry['source_url'])->toStartWith('https://inivie.com/')
            ->and($entry['title'])->not->toBeEmpty()
            ->and(mb_strlen($entry['content']))->toBeGreaterThanOrEqual(120)
            ->and(mb_strlen($entry['content']))->toBeLessThanOrEqual(20000);
    }
});

test('inivie knowledge seeder dispatches chunked import batch', function () {
    Bus::fake();
    $entries = inivieKnowledgeFixture();

    app(IniVieKnowledgeSeeder::class)->run();

    Bus::assertBatchCount(1);
    Bus::assertBatched(function (PendingBatch $batch) use ($entries): bool {
        return $batch->name === 'Import Ini Vie knowledge'
            && $batch->jobs->count() === (int) ceil(count($entries) / 20)
            && $batch->jobs->every(fn (object $job): bool => $job instanceof ImportIniVieKnowledgeChunk && count($job->entries) <= 20);
    });
});

test('knowledge import job embeds its chunk once and is idempotent', function () {
    $calls = 0;
    $entries = array_slice(inivieKnowledgeFixture(), 0, 20);
    Log::spy();
    Embeddings::fake(function (EmbeddingsPrompt $prompt) use (&$calls): array {
        $calls++;

        return array_fill(0, count($prompt), array_fill(0, 1024, 0.0));
    });

    $job = new ImportIniVieKnowledgeChunk($entries);
    $job->handle(app(KnowledgeEmbeddingService::class));

    expect(KnowledgeItem::query()->count())->toBe(count($entries))
        ->and(KnowledgeItem::query()->where('status', 'published')->count())->toBe(count($entries))
        ->and(KnowledgeItem::query()->whereNull('embedding')->count())->toBe(0)
        ->and($calls)->toBe(1);

    Log::shouldHaveReceived('info')->with(
        'concierge_knowledge_import_completed',
        Mockery::on(fn (array $context): bool => $context['entry_count'] === count($entries) && $context['embedded_count'] === count($entries)),
    )->once();

    $job->handle(app(KnowledgeEmbeddingService::class));

    expect(KnowledgeItem::query()->count())->toBe(count($entries))
        ->and($calls)->toBe(1);
});

test('knowledge import job rolls back its chunk when embedding fails', function () {
    Log::spy();
    Embeddings::fake(fn () => throw new RuntimeException('Ollama unavailable'));
    $job = new ImportIniVieKnowledgeChunk(array_slice(inivieKnowledgeFixture(), 0, 20));

    expect(fn () => $job->handle(app(KnowledgeEmbeddingService::class)))
        ->toThrow(RuntimeException::class, 'Ollama unavailable');

    expect(KnowledgeItem::query()->count())->toBe(0);

    Log::shouldHaveReceived('error')->with(
        'concierge_knowledge_import_failed',
        Mockery::on(fn (array $context): bool => $context['entry_count'] === 20 && $context['exception'] instanceof RuntimeException),
    )->once();
});

test('knowledge import job uses safe queue retry settings', function () {
    $job = new ImportIniVieKnowledgeChunk([]);

    expect($job->timeout)->toBe(180)
        ->and($job->tries)->toBe(3)
        ->and($job->backoff())->toBe([10, 60, 180])
        ->and($job->queue)->toBeNull();
});
