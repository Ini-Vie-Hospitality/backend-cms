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
        ->assertJsonPath('popup.image', 'https://inivie.com/voting-popup/pop-iniviehos.webp')
        ->assertJsonPath('popup.href', 'https://exquisite-awards.com/awards/2026/categories/best-unique-concept-hotel-group?nominee=ini-vie-hospitality')
        ->assertJsonPath('navbar.book.label', 'Book Your Stay')
        ->assertJsonPath('brandIntroduction.title', 'iNi ViE Hospitality')
        ->assertJsonPath('featuredProperties.items.0.name', 'Leedon Villa Seminyak')
        ->assertJsonCount(3, 'featuredProperties.items')
        ->assertJsonCount(6, 'culinary.items')
        ->assertJsonPath('culinary.items.2.name', 'Terra Verte')
        ->assertJsonPath('culinary.items.5.name', 'Seven Paintings')
        ->assertJsonCount(3, 'wellness.items')
        ->assertJsonPath('membership.primary.href', 'https://booking.inivie.com/register')
        ->assertJsonCount(4, 'membership.benefits')
        ->assertJsonCount(4, 'ourStory.blocks')
        ->assertJsonPath('ourStory.blocks.1.href', 'https://inivie.com/why-choose-us')
        ->assertJsonCount(3, 'specialOffers.items')
        ->assertJsonPath('specialOffers.items.0.title', 'INI VIE X TAP CLUB CANGGU')
        ->assertJsonCount(6, 'whatsNew.items')
        ->assertJsonPath('whatsNew.items.0.id', 'best-restaurants-for-date-night')
        ->assertJsonCount(9, 'featuredIn.items')
        ->assertJsonCount(9, 'faq.items')
        ->assertJsonPath('faq.items.0.question', 'What is Ini Vie Hospitality?')
        ->assertJsonPath('footer.office.email.label', 'info@inivie.com')
        ->assertJsonPath('footer.office.map.href', 'https://maps.app.goo.gl/uzTWDwHNTNhyLxH28')
        ->assertJsonCount(6, 'footer.contacts')
        ->assertJsonCount(5, 'footer.socials');
});

test('draft sections and items are excluded and ordering is deterministic', function () {
    DB::table('homepage_faq_items')->where('question', 'What is Ini Vie Hospitality?')->update(['status' => 'draft', 'published_at' => null]);
    DB::table('homepage_faq_items')->where('question', 'Where are Ini Vie Hospitality properties located?')->update(['sort_order' => 0]);

    $this->getJson('/api/homepage')->assertOk()
        ->assertJsonCount(8, 'faq.items')
        ->assertJsonPath('faq.items.0.question', 'Where are Ini Vie Hospitality properties located?');

    DB::table('homepage_faq_sections')->update(['status' => 'draft', 'published_at' => null]);
    $this->getJson('/api/homepage')->assertJsonPath('faq', null);
});

test('homepage schema contains no generic content columns', function () {
    foreach (['homepage_navbars', 'homepage_brand_introductions', 'homepage_featured_properties', 'homepage_journal_stories', 'homepage_footers'] as $table) {
        expect(Schema::hasColumn($table, 'content'))->toBeFalse();
    }
});
