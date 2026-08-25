<?php

use App\Models\User;
use Database\Seeders\HomepageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HomepageSeeder::class);
});

test('guests cannot manage homepage content', function () {
    $this->get('/cms/homepage/navbar')->assertRedirect('/login');
});

test('homepage preview uses the configured frontend origin', function () {
    config(['services.homepage.frontend_url' => 'http://localhost:3000']);

    $this->actingAs(User::factory()->create())
        ->get('/cms/homepage/preview')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('homepage/preview')
            ->where('publishedUrl', 'http://localhost:3000/')
            ->where('draftUrl', fn ($url) => str_starts_with($url, 'http://localhost:3000/preview?expires=')));
});

test('each section renders its own inertia page through the default layout', function () {
    $user = User::factory()->create();
    foreach (['navbar' => 'navbar/edit', 'brand-introduction' => 'brand-introduction/edit', 'featured-properties' => 'featured-properties/index', 'culinary' => 'culinary/index', 'wellness' => 'wellness/index', 'membership' => 'membership/edit', 'our-story' => 'our-story/edit', 'special-offers' => 'special-offers/edit', 'whats-new' => 'whats-new/index', 'featured-in' => 'featured-in/index', 'faq' => 'faq/index', 'footer' => 'footer/edit'] as $uri => $component) {
        $this->actingAs($user)->get("/cms/homepage/$uri")->assertOk()->assertInertia(fn (Assert $page) => $page->component("homepage/$component"));
    }
});

test('published mutations revalidate the Next.js homepage after redirects', function () {
    config([
        'services.homepage.revalidate_url' => 'http://localhost:3000/api/revalidate',
        'services.homepage.revalidate_secret' => 'revalidate-secret',
    ]);
    Http::fake();

    $this->actingAs(User::factory()->create())
        ->put('/cms/homepage/faq', [
            'eyebrow' => 'Help',
            'title' => 'Updated questions',
            'description' => 'Updated answers',
            'status' => 'published',
        ])
        ->assertRedirect();

    Http::assertSent(fn ($request) => $request->url() === 'http://localhost:3000/api/revalidate'
        && $request->hasHeader('Authorization', 'Bearer revalidate-secret'));
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

test('collection edit routes bind item before the default section parameter', function () {
    $user = User::factory()->create();
    $routes = [
        'featured-properties' => 'homepage_featured_properties',
        'culinary' => 'homepage_culinary_destinations',
        'wellness' => 'homepage_wellness_escapes',
        'whats-new' => 'homepage_journal_stories',
        'featured-in' => 'homepage_featured_in_logos',
        'faq' => 'homepage_faq_items',
    ];

    foreach ($routes as $section => $table) {
        $item = DB::table($table)->first();
        $this->actingAs($user)
            ->get("/cms/homepage/$section/items/$item->id/edit")
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component("homepage/$section/edit")
                ->where('item.id', $item->id));
    }
});

test('collection update and delete routes preserve item then section binding', function () {
    $user = User::factory()->create();
    $item = DB::table('homepage_faq_items')->first();
    $payload = ['question' => 'Updated question', 'answer' => 'Updated answer', 'sort_order' => 1, 'status' => 'published'];

    $this->actingAs($user)
        ->put("/cms/homepage/faq/items/$item->id", $payload)
        ->assertRedirect()
        ->assertSessionHasNoErrors();
    expect(DB::table('homepage_faq_items')->where('id', $item->id)->value('question'))->toBe('Updated question');

    $this->actingAs($user)
        ->delete("/cms/homepage/faq/items/$item->id")
        ->assertRedirect();
    expect(DB::table('homepage_faq_items')->where('id', $item->id)->exists())->toBeFalse();
});

test('file uploads use laravel public storage', function () {
    Storage::fake('public');
    $payload = ['name' => 'Villa', 'category' => 'Stay', 'description' => 'Private', 'image_alt' => 'Villa', 'href' => 'https://example.test/villa', 'cta_label' => 'Explore', 'sort_order' => 9, 'status' => 'published', 'image' => UploadedFile::fake()->image('villa.webp')];
    $this->actingAs(User::factory()->create())->post('/cms/homepage/featured-properties/items', $payload)->assertRedirect()->assertSessionHasNoErrors();
    Storage::disk('public')->assertExists(DB::table('homepage_featured_properties')->where('name', 'Villa')->value('image_path'));
});

test('homepage cms pages expose image preview urls', function () {
    $imagePath = 'homepage/preview.webp';
    DB::table('homepage_brand_introduction_images')->orderBy('slot')->limit(1)->update(['image_path' => $imagePath]);
    foreach (['homepage_featured_properties', 'homepage_culinary_destinations', 'homepage_wellness_escapes', 'homepage_journal_stories', 'homepage_featured_in_logos', 'homepage_story_blocks', 'homepage_special_offers'] as $table) {
        DB::table($table)->limit(1)->update(['image_path' => $imagePath]);
    }

    $user = User::factory()->create();
    $imageUrl = url('/storage/'.$imagePath);

    $this->actingAs($user)->get('/cms/homepage/brand-introduction')->assertInertia(fn (Assert $page) => $page->where('record.image_1_url', $imageUrl));
    foreach (['featured-properties', 'culinary', 'wellness', 'whats-new', 'featured-in'] as $section) {
        $this->actingAs($user)->get("/cms/homepage/$section")->assertInertia(fn (Assert $page) => $page->where('items.0.image_url', $imageUrl));
    }
    $this->actingAs($user)->get('/cms/homepage/our-story')->assertInertia(fn (Assert $page) => $page->where('blocks.0.image_url', $imageUrl));
    $this->actingAs($user)->get('/cms/homepage/special-offers')->assertInertia(fn (Assert $page) => $page->where('items.0.image_url', $imageUrl));
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

test('membership benefits accept supported hospitality icons', function () {
    $this->actingAs(User::factory()->create())
        ->post('/cms/homepage/membership/benefits', [
            'label' => 'Priority dining access',
            'icon' => 'crown',
            'sort_order' => 4,
            'is_active' => true,
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect(DB::table('homepage_membership_benefits')->where('label', 'Priority dining access')->value('icon'))->toBe('crown');
});

test('membership benefits reject unsupported icons', function () {
    $this->actingAs(User::factory()->create())
        ->post('/cms/homepage/membership/benefits', [
            'label' => 'Unknown benefit',
            'icon' => 'unknown-icon',
            'sort_order' => 4,
            'is_active' => true,
        ])
        ->assertSessionHasErrors('icon');
});
