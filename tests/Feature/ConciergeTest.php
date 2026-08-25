<?php

use App\Models\Concierge\KnowledgeItem;
use App\Models\User;
use App\Services\Concierge\KnowledgeSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Ai\Embeddings;

uses(RefreshDatabase::class);

test('guests cannot manage concierge knowledge', function () {
    $this->get('/cms/concierge/knowledge')->assertRedirect('/login');
});

test('knowledge index supports configurable page sizes', function () {
    $user = User::factory()->create();
    foreach (range(1, 55) as $index) {
        KnowledgeItem::query()->create(['title' => "Knowledge {$index}", 'content' => "Content {$index}", 'status' => 'draft']);
    }

    $this->actingAs($user)->get('/cms/concierge/knowledge?per_page=50')->assertInertia(fn (Assert $page) => $page
        ->where('items.data', fn ($items) => count($items) === 50)
        ->where('items.current_page', 1)
        ->where('items.last_page', 2)
        ->where('items.per_page', 50)
        ->where('items.total', 55));

    $this->actingAs($user)->get('/cms/concierge/knowledge')->assertInertia(fn (Assert $page) => $page
        ->where('items.data', fn ($items) => count($items) === 10)
        ->where('items.per_page', 10));

    $this->actingAs($user)->get('/cms/concierge/knowledge?per_page=100')->assertInertia(fn (Assert $page) => $page
        ->where('items.data', fn ($items) => count($items) === 55)
        ->where('items.last_page', 1)
        ->where('items.per_page', 100));

    $this->actingAs($user)->get('/cms/concierge/knowledge?per_page=all')->assertInertia(fn (Assert $page) => $page
        ->where('items.data', fn ($items) => count($items) === 55)
        ->where('items.last_page', 1)
        ->where('items.per_page', 'all'));

    $this->actingAs($user)->get('/cms/concierge/knowledge?per_page=invalid')->assertInertia(fn (Assert $page) => $page
        ->where('items.data', fn ($items) => count($items) === 10)
        ->where('items.per_page', 10));
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
