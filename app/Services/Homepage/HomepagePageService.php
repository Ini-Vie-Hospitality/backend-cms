<?php

namespace App\Services\Homepage;

use App\Services\Homepage\Content\ContentMedia;
use App\Support\HomepageDefinitions;
use Illuminate\Support\Facades\DB;

class HomepagePageService
{
    public function __construct(private HomepageWorkspaceContext $workspace, private ContentMedia $media) {}

    /** @return array<string, mixed> */
    public function navbar(): array
    {
        $record = $this->workspace->root('homepage_navbars')->firstOrFail();

        return ['record' => $record, 'links' => DB::table('homepage_navbar_links')->where('navbar_id', $record->id)->orderBy('audience')->orderBy('sort_order')->get()];
    }

    /** @return array<string, mixed> */
    public function popup(): array
    {
        $record = $this->workspace->root('homepage_popups')->firstOrFail();
        $record->image_url = $this->media->url($record->image_path);

        return ['record' => $record];
    }

    /** @return array<string, mixed> */
    public function brandIntroduction(): array
    {
        $record = $this->workspace->root('homepage_brand_introductions')->firstOrFail();
        $words = DB::table('homepage_brand_introduction_words')->orderBy('slot')->pluck('text')->all();
        $paragraphs = DB::table('homepage_brand_introduction_paragraphs')->orderBy('slot')->pluck('body')->all();
        $images = DB::table('homepage_brand_introduction_images')->orderBy('slot')->get();

        return ['record' => [...get_object_vars($record), 'word_1' => $words[0] ?? '', 'word_2' => $words[1] ?? '', 'paragraph_1' => $paragraphs[0] ?? '', 'paragraph_2' => $paragraphs[1] ?? '', 'image_alt_1' => $images[0]->image_alt ?? '', 'image_alt_2' => $images[1]->image_alt ?? '', 'image_alt_3' => $images[2]->image_alt ?? '', 'image_1_url' => $this->media->url($images[0]->image_path ?? null), 'image_2_url' => $this->media->url($images[1]->image_path ?? null), 'image_3_url' => $this->media->url($images[2]->image_path ?? null)]];
    }

    /** @return array<string, mixed> */
    public function section(string $section): array
    {
        $definition = HomepageDefinitions::section($section);
        $record = $this->workspace->root($definition['table'])->firstOrFail();
        $props = ['record' => $record];
        $relations = [
            'featured-properties' => ['items', 'homepage_featured_properties', 'sort_order'], 'culinary' => ['items', 'homepage_culinary_destinations', 'sort_order'],
            'wellness' => ['items', 'homepage_wellness_escapes', 'sort_order'], 'membership' => ['benefits', 'homepage_membership_benefits', 'sort_order'],
            'our-story' => ['blocks', 'homepage_story_blocks', 'slot'], 'special-offers' => ['items', 'homepage_special_offers', 'slot'],
            'whats-new' => ['items', 'homepage_journal_stories', 'sort_order'], 'featured-in' => ['items', 'homepage_featured_in_logos', 'sort_order'],
            'faq' => ['items', 'homepage_faq_items', 'sort_order'], 'footer' => ['contacts', 'homepage_footer_contacts', 'sort_order'],
        ];
        if (isset($relations[$section])) {
            [$key, $table, $order] = $relations[$section];
            $foreignKey = in_array($table, ['homepage_membership_benefits'], true) ? 'membership_id' : (in_array($table, ['homepage_footer_contacts'], true) ? 'footer_id' : 'section_id');
            $props[$key] = DB::table($table)->where($foreignKey, $record->id)->orderBy($order)->get()->each(fn (\stdClass $item) => $this->withImageUrl($item));
        }
        if ($section === 'wellness') {
            $props['categories'] = $this->workspace->root('homepage_wellness_categories')->orderBy('name')->get();
        }
        if ($section === 'footer') {
            $props['socials'] = DB::table('homepage_footer_socials')->where('footer_id', $record->id)->orderBy('sort_order')->get();
        }

        return $props;
    }

    public function item(string $section, int $item): \stdClass
    {
        $definition = HomepageDefinitions::item($section);
        $parentId = $this->workspace->root($definition['parent'])->value('id');
        $record = DB::table($definition['table'])->where('section_id', $parentId)->where('id', $item)->first();
        if ($record === null) {
            abort(404);
        }

        return $this->withImageUrl($record);
    }

    private function withImageUrl(\stdClass $record): \stdClass
    {
        if (property_exists($record, 'image_path')) {
            $record->image_url = $this->media->url($record->image_path);
        }

        return $record;
    }
}
