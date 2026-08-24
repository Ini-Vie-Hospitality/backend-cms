<?php

use Database\Seeders\HomepageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HomepageSeeder::class);
});

test('public homepage matches the relational contract', function () {
    $this->getJson('/api/homepage')->assertOk()
        ->assertJsonPath('navbar.book.label', 'Book Your Stay')
        ->assertJsonPath('featuredProperties.items.0.name', 'Leedon Villa Seminyak')
        ->assertJsonCount(4, 'ourStory.blocks')
        ->assertJsonCount(3, 'specialOffers.items')
        ->assertJsonPath('footer.office.email.label', 'info@inivie.com');
});

test('draft sections and items are excluded and ordering is deterministic', function () {
    DB::table('homepage_faq_items')->where('question', 'What Is Ini Vie Hospitality?')->update(['status' => 'draft', 'published_at' => null]);
    DB::table('homepage_faq_items')->where('question', 'How Can I Get The Best Rate When Booking?')->update(['sort_order' => 0]);

    $this->getJson('/api/homepage')->assertOk()
        ->assertJsonCount(1, 'faq.items')
        ->assertJsonPath('faq.items.0.question', 'How Can I Get The Best Rate When Booking?');

    DB::table('homepage_faq_sections')->update(['status' => 'draft', 'published_at' => null]);
    $this->getJson('/api/homepage')->assertJsonPath('faq', null);
});

test('homepage schema contains no generic content columns', function () {
    foreach (['homepage_navbars', 'homepage_brand_introductions', 'homepage_featured_properties', 'homepage_journal_stories', 'homepage_footers'] as $table) {
        expect(Schema::hasColumn($table, 'content'))->toBeFalse();
    }
});
