<?php

namespace App\Services\Homepage;

use App\Support\HomepageDefinitions;
use Illuminate\Support\Facades\DB;

class HomepagePageService
{
    /** @return array<string, mixed> */
    public function navbar(): array
    {
        return ['record' => DB::table('homepage_navbars')->first(), 'links' => DB::table('homepage_navbar_links')->orderBy('audience')->orderBy('sort_order')->get()];
    }

    /** @return array<string, mixed> */
    public function brandIntroduction(): array
    {
        $record = DB::table('homepage_brand_introductions')->firstOrFail();
        $words = DB::table('homepage_brand_introduction_words')->orderBy('slot')->pluck('text')->all();
        $paragraphs = DB::table('homepage_brand_introduction_paragraphs')->orderBy('slot')->pluck('body')->all();
        $images = DB::table('homepage_brand_introduction_images')->orderBy('slot')->get();

        return ['record' => [...get_object_vars($record), 'word_1' => $words[0] ?? '', 'word_2' => $words[1] ?? '', 'paragraph_1' => $paragraphs[0] ?? '', 'paragraph_2' => $paragraphs[1] ?? '', 'image_alt_1' => $images[0]->image_alt ?? '', 'image_alt_2' => $images[1]->image_alt ?? '', 'image_alt_3' => $images[2]->image_alt ?? '']];
    }

    /** @return array<string, mixed> */
    public function section(string $section): array
    {
        $definition = HomepageDefinitions::section($section);
        $props = ['record' => DB::table($definition['table'])->first()];
        $relations = [
            'featured-properties' => ['items', 'homepage_featured_properties', 'sort_order'], 'culinary' => ['items', 'homepage_culinary_destinations', 'sort_order'],
            'wellness' => ['items', 'homepage_wellness_escapes', 'sort_order'], 'membership' => ['benefits', 'homepage_membership_benefits', 'sort_order'],
            'our-story' => ['blocks', 'homepage_story_blocks', 'slot'], 'special-offers' => ['items', 'homepage_special_offers', 'slot'],
            'whats-new' => ['items', 'homepage_journal_stories', 'sort_order'], 'featured-in' => ['items', 'homepage_featured_in_logos', 'sort_order'],
            'faq' => ['items', 'homepage_faq_items', 'sort_order'], 'footer' => ['contacts', 'homepage_footer_contacts', 'sort_order'],
        ];
        if (isset($relations[$section])) {
            [$key, $table, $order] = $relations[$section];
            $props[$key] = DB::table($table)->orderBy($order)->get();
        }
        if ($section === 'wellness') {
            $props['categories'] = DB::table('homepage_wellness_categories')->orderBy('name')->get();
        }
        if ($section === 'footer') {
            $props['socials'] = DB::table('homepage_footer_socials')->orderBy('sort_order')->get();
        }

        return $props;
    }

    public function item(string $section, int $item): \stdClass
    {
        $record = DB::table(HomepageDefinitions::item($section)['table'])->where('id', $item)->first();
        if ($record === null) {
            abort(404);
        }

        return $record;
    }
}
