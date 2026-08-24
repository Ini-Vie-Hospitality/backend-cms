<?php

namespace App\Services\Homepage\Content;

use App\Services\Homepage\HomepageWorkspaceContext;
use Illuminate\Support\Facades\DB;

class ShowcaseContentService
{
    public function __construct(private ContentMedia $media, private HomepageWorkspaceContext $workspace) {}

    /** @return array<string, mixed>|null */
    public function featuredProperties(): ?array
    {
        return $this->collectionSection('homepage_featured_property_sections', 'homepage_featured_properties', fn ($row) => ['id' => $row->id, 'name' => $row->name, 'category' => $row->category, 'description' => $row->description, 'image' => $this->media->url($row->image_path), 'alt' => $row->image_alt, 'href' => $row->href, 'cta' => $row->cta_label], ['cta' => 'default_cta_label', 'scrollLabel' => 'scroll_label']);
    }

    /** @return array<string, mixed>|null */
    public function culinary(): ?array
    {
        return $this->collectionSection('homepage_culinary_sections', 'homepage_culinary_destinations', fn ($row) => ['name' => $row->name, 'location' => $row->location, 'eyebrow' => $row->eyebrow, 'description' => $row->description, 'schedule' => $row->schedule, 'ctaLabel' => $row->cta_label, 'image' => $this->media->url($row->image_path), 'alt' => $row->image_alt, 'href' => $row->href], ['scrollLabel' => 'scroll_label']);
    }

    /** @return array<string, mixed>|null */
    public function wellness(): ?array
    {
        return $this->collectionSection('homepage_wellness_sections', 'homepage_wellness_escapes', function ($row) {
            $categories = DB::table('homepage_wellness_escape_category as pivot')->join('homepage_wellness_categories as categories', 'categories.id', '=', 'pivot.wellness_category_id')->where('pivot.wellness_escape_id', $row->id)->orderBy('pivot.sort_order')->pluck('categories.name')->all();

            return ['id' => $row->id, 'categories' => $categories, 'name' => $row->name, 'location' => $row->location, 'description' => $row->description, 'image' => $this->media->url($row->image_path), 'alt' => $row->image_alt, 'href' => $row->href, 'cta' => $row->cta_label];
        });
    }

    /** @return array<string, mixed>|null */
    public function faq(): ?array
    {
        return $this->collectionSection('homepage_faq_sections', 'homepage_faq_items', fn ($row) => ['question' => $row->question, 'answer' => $row->answer]);
    }

    /**
     * @param  callable(\stdClass): array<string, mixed>  $transform
     * @param  array<string, string>  $aliases
     * @return array<string, mixed>|null
     */
    private function collectionSection(string $sectionTable, string $itemTable, callable $transform, array $aliases = []): ?array
    {
        $row = $this->workspace->visible($this->workspace->root($sectionTable))->first();
        if (! $row) {
            return null;
        }

        $result = ['eyebrow' => $row->eyebrow, 'title' => $row->title, 'description' => $row->description];
        foreach ($aliases as $target => $source) {
            $result[$target] = $row->{$source};
        }
        $result['items'] = $this->workspace->visible(DB::table($itemTable)->where('section_id', $row->id))->orderBy('sort_order')->orderBy('id')->get()->map($transform)->all();

        return $result;
    }
}
