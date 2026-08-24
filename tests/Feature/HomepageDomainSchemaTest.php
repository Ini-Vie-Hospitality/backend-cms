<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('homepage uses relational singleton tables without content json', function () {
    foreach ([
        'homepage_navbars' => ['logo_path', 'book_label', 'status', 'published_at'],
        'homepage_brand_introductions' => ['title', 'quote', 'status', 'published_at'],
        'homepage_featured_property_sections' => ['eyebrow', 'title', 'default_cta_label', 'status'],
        'homepage_memberships' => ['title', 'video_path', 'primary_label', 'status'],
        'homepage_footers' => ['aria_label', 'background_image_path', 'copyright', 'status'],
    ] as $table => $columns) {
        expect(Schema::hasTable($table))->toBeTrue();
        foreach ($columns as $column) {
            expect(Schema::hasColumn($table, $column))->toBeTrue();
        }
        expect(Schema::hasColumn($table, 'content'))->toBeFalse();
    }
});

test('homepage uses related collection and nested tables', function () {
    foreach ([
        'homepage_navbar_links' => ['navbar_id', 'audience', 'label', 'href'],
        'homepage_featured_properties' => ['section_id', 'name', 'image_path'],
        'homepage_culinary_destinations' => ['section_id', 'name', 'image_path'],
        'homepage_wellness_escapes' => ['section_id', 'name', 'image_path'],
        'homepage_wellness_escape_category' => ['wellness_escape_id', 'wellness_category_id'],
        'homepage_story_blocks' => ['section_id', 'slot', 'title', 'image_path'],
        'homepage_special_offers' => ['section_id', 'slot', 'display_number', 'image_path'],
        'homepage_journal_stories' => ['section_id', 'external_key', 'image_path'],
        'homepage_journal_story_title_lines' => ['story_id', 'line_number', 'text'],
        'homepage_footer_contact_actions' => ['contact_id', 'label', 'href'],
    ] as $table => $columns) {
        expect(Schema::hasTable($table))->toBeTrue();
        foreach ($columns as $column) {
            expect(Schema::hasColumn($table, $column))->toBeTrue();
        }
        expect(Schema::hasColumn($table, 'content'))->toBeFalse();
    }
});
