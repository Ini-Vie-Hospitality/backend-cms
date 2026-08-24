<?php

use App\Models\User;
use Database\Seeders\HomepageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HomepageSeeder::class);
});

test('guests cannot manage homepage content', function () {
    $this->get('/cms/homepage/navbar')->assertRedirect('/login');
});

test('each section renders its own inertia page through the default layout', function () {
    $user = User::factory()->create();
    foreach (['navbar' => 'navbar/edit', 'brand-introduction' => 'brand-introduction/edit', 'featured-properties' => 'featured-properties/index', 'culinary' => 'culinary/index', 'wellness' => 'wellness/index', 'membership' => 'membership/edit', 'our-story' => 'our-story/edit', 'special-offers' => 'special-offers/edit', 'whats-new' => 'whats-new/index', 'featured-in' => 'featured-in/index', 'faq' => 'faq/index', 'footer' => 'footer/edit'] as $uri => $component) {
        $this->actingAs($user)->get("/cms/homepage/$uri")->assertOk()->assertInertia(fn (Assert $page) => $page->component("homepage/$component"));
    }
});

test('section forms use relational fields and publication status', function () {
    $this->actingAs(User::factory()->create())->put('/cms/homepage/faq', ['eyebrow' => 'Help', 'title' => 'Questions', 'description' => 'Answers', 'status' => 'published'])->assertRedirect()->assertSessionHasNoErrors();
    expect(DB::table('homepage_faq_sections')->value('title'))->toBe('Questions')->and(DB::table('homepage_faq_sections')->value('published_at'))->not->toBeNull();
});

test('collection create update delete uses explicit relational columns', function () {
    $user = User::factory()->create();
    $payload = ['question' => 'Question', 'answer' => 'Answer', 'sort_order' => 5, 'status' => 'published'];
    $this->actingAs($user)->post('/cms/homepage/faq/items', $payload)->assertRedirect()->assertSessionHasNoErrors();
    $item = DB::table('homepage_faq_items')->where('question', 'Question')->first();
    $this->actingAs($user)->put("/cms/homepage/faq/items/$item->id", [...$payload, 'answer' => 'Changed'])->assertRedirect();
    expect(DB::table('homepage_faq_items')->where('id', $item->id)->value('answer'))->toBe('Changed');
    $this->actingAs($user)->delete("/cms/homepage/faq/items/$item->id")->assertRedirect();
    expect(DB::table('homepage_faq_items')->where('id', $item->id)->exists())->toBeFalse();
});

test('file uploads use laravel public storage', function () {
    Storage::fake('public');
    $payload = ['name' => 'Villa', 'category' => 'Stay', 'description' => 'Private', 'image_alt' => 'Villa', 'href' => 'https://example.test/villa', 'cta_label' => 'Explore', 'sort_order' => 9, 'status' => 'published', 'image' => UploadedFile::fake()->image('villa.webp')];
    $this->actingAs(User::factory()->create())->post('/cms/homepage/featured-properties/items', $payload)->assertRedirect()->assertSessionHasNoErrors();
    Storage::disk('public')->assertExists(DB::table('homepage_featured_properties')->where('name', 'Villa')->value('image_path'));
});

test('fixed story and offer slots have update routes but no create or delete routes', function () {
    $user = User::factory()->create();
    $block = DB::table('homepage_story_blocks')->first();
    $this->actingAs($user)->put("/cms/homepage/our-story/blocks/$block->id", ['title' => 'Updated', 'description' => 'Body', 'image_alt' => 'Alt', 'cta_label' => 'Read', 'href' => 'https://example.test', 'status' => 'published'])->assertRedirect();
    expect(DB::table('homepage_story_blocks')->where('id', $block->id)->value('title'))->toBe('Updated');
    $this->actingAs($user)->post('/cms/homepage/our-story/items', [])->assertNotFound();
});

test('source contains no raw json cms editor', function () {
    $files = collect(glob(resource_path('js/pages/homepage/**/*.tsx')))->merge(glob(resource_path('js/pages/homepage/*.tsx')));
    $source = $files->map(fn ($file) => file_get_contents($file))->implode("\n");
    expect($source)->not->toContain('JSON.parse')->not->toContain('JSON.stringify')->not->toContain('content_json');
});
