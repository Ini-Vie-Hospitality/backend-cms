<?php

namespace App\Services\Homepage\Content;

use Illuminate\Support\Facades\DB;

class EditorialContentService
{
    public function __construct(private ContentMedia $media) {}

    /** @return array<string, mixed>|null */
    public function membership(): ?array
    {
        $row = $this->publishedRow('homepage_memberships');
        if (! $row) {
            return null;
        }
        $benefits = DB::table('homepage_membership_benefits')->where('membership_id', $row->id)->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => ['label' => $item->label, 'icon' => $item->icon])->all();

        return ['title' => $row->title, 'subtitle' => $row->subtitle, 'description' => $row->description, 'video' => $this->media->url($row->video_path), 'primary' => ['label' => $row->primary_label, 'href' => $row->primary_href], 'secondary' => ['label' => $row->secondary_label, 'href' => $row->secondary_href], 'benefits' => $benefits];
    }

    /** @return array<string, mixed>|null */
    public function ourStory(): ?array
    {
        $row = $this->publishedRow('homepage_story_sections');
        if (! $row) {
            return null;
        }
        $blocks = DB::table('homepage_story_blocks')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('slot')->get()->map(fn ($item) => ['title' => $item->title, 'description' => $item->description, 'image' => $this->media->url($item->image_path), 'alt' => $item->image_alt, 'cta' => $item->cta_label, 'href' => $item->href])->all();

        return count($blocks) === 4 ? ['title' => $row->title, 'description' => $row->description, 'blocks' => $blocks] : null;
    }

    /** @return array<string, mixed>|null */
    public function specialOffers(): ?array
    {
        $row = $this->publishedRow('homepage_special_offer_sections');
        if (! $row) {
            return null;
        }
        $items = DB::table('homepage_special_offers')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('slot')->get()->map(fn ($item) => ['id' => $item->display_number, 'category' => $item->category, 'title' => $item->title, 'description' => $item->description, 'image' => $this->media->url($item->image_path), 'alt' => $item->image_alt, 'href' => $item->href])->all();

        return count($items) === 3 ? ['eyebrow' => $row->eyebrow, 'title' => $row->title, 'description' => $row->description, 'allOffers' => ['label' => $row->all_offers_label, 'href' => $row->all_offers_href], 'items' => $items] : null;
    }

    /** @return array<string, mixed>|null */
    public function whatsNew(): ?array
    {
        $row = $this->publishedRow('homepage_journal_sections');
        if (! $row) {
            return null;
        }
        $items = DB::table('homepage_journal_stories')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('sort_order')->orderBy('id')->get()->map(fn ($item) => ['id' => $item->external_key, 'category' => $item->category, 'title' => DB::table('homepage_journal_story_title_lines')->where('story_id', $item->id)->orderBy('line_number')->pluck('text')->all(), 'description' => $item->description, 'readingTime' => $item->reading_time, 'image' => $this->media->url($item->image_path), 'alt' => $item->image_alt, 'href' => $item->href])->all();

        return ['eyebrow' => $row->eyebrow, 'title' => $row->title, 'description' => $row->description, 'explore' => ['label' => $row->explore_label, 'href' => $row->explore_href], 'readLabel' => $row->read_label, 'items' => $items];
    }

    /** @return array<string, mixed>|null */
    public function featuredIn(): ?array
    {
        $row = $this->publishedRow('homepage_featured_in_sections');
        if (! $row) {
            return null;
        }

        return ['title' => $row->title, 'items' => DB::table('homepage_featured_in_logos')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('sort_order')->get()->map(fn ($item) => ['src' => $this->media->url($item->image_path), 'alt' => $item->image_alt])->all()];
    }

    private function publishedRow(string $table): ?\stdClass
    {
        return DB::table($table)->where('status', 'published')->whereNotNull('published_at')->first();
    }
}
