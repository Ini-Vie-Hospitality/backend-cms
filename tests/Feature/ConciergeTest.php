<?php

use App\Models\Concierge\KnowledgeItem;
use App\Models\User;
use App\Services\Concierge\KnowledgeSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Embeddings;

uses(RefreshDatabase::class);

test('guests cannot manage concierge knowledge', function () {
    $this->get('/cms/concierge/knowledge')->assertRedirect('/login');
});

test('authenticated users can manage draft knowledge without calling providers', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/cms/concierge/knowledge', [
        'title' => 'Airport Transfer',
        'category' => 'Transport',
        'content' => 'Airport transfer can be arranged through the hospitality team.',
        'status' => 'draft',
    ])->assertRedirect('/cms/concierge/knowledge')->assertSessionHasNoErrors();

    $item = KnowledgeItem::query()->firstOrFail();
    expect($item->content_hash)->toBe(hash('sha256', 'Airport Transfer|Airport transfer can be arranged through the hospitality team.'));

    $this->actingAs($user)->put("/cms/concierge/knowledge/{$item->id}", [
        'title' => 'Airport Transfer',
        'category' => 'Transport',
        'content' => 'Contact the hospitality team to arrange a transfer.',
        'status' => 'draft',
    ])->assertRedirect('/cms/concierge/knowledge')->assertSessionHasNoErrors();

    $this->actingAs($user)->delete("/cms/concierge/knowledge/{$item->id}")->assertRedirect();
    expect(KnowledgeItem::query()->exists())->toBeFalse();
});

test('changing knowledge content clears its stale embedding', function () {
    $item = KnowledgeItem::query()->create(['title' => 'Dining', 'content' => 'Original', 'status' => 'draft']);
    $item->newQuery()->whereKey($item)->update(['embedding' => '[0.1]']);

    $item->refresh()->update(['content' => 'Changed']);

    expect($item->refresh()->embedding)->toBeNull();
});

test('knowledge retrieval returns at most twenty candidates', function () {
    foreach (range(1, 25) as $index) {
        KnowledgeItem::query()->create([
            'title' => "Knowledge {$index}",
            'content' => "Published knowledge content {$index}.",
            'embedding' => '[0.0]',
            'status' => 'published',
        ]);
    }

    Embeddings::fake(fn (): array => [array_fill(0, 1024, 0.0)]);

    $results = app(KnowledgeSearchService::class)->search('published knowledge');

    expect(config('concierge.result_limit'))->toBe(20)
        ->and($results)->toHaveCount(20);
});

test('chat returns a safe handoff when no indexed knowledge exists', function () {
    $this->postJson('/api/concierge/chat', ['message' => 'Apakah ada airport transfer?', 'history' => []])
        ->assertOk()->assertJsonPath('handoff', true)->assertJsonCount(0, 'sources');
});

test('chat accepts at most five history turns', function () {
    $history = collect(range(1, 11))->map(fn (int $index) => ['role' => $index % 2 ? 'user' : 'assistant', 'content' => "Message {$index}"])->all();

    $this->postJson('/api/concierge/chat', ['message' => 'Hello', 'history' => $history])
        ->assertUnprocessable()->assertJsonValidationErrors('history');
});
