<?php

namespace App\Services\Homepage\Content;

use Illuminate\Support\Facades\DB;

class NavigationContentService
{
    public function __construct(private ContentMedia $media) {}

    /** @return array<string, mixed>|null */
    public function navbar(): ?array
    {
        $row = $this->publishedRow('homepage_navbars');
        if (! $row) {
            return null;
        }

        $links = DB::table('homepage_navbar_links')->where('navbar_id', $row->id)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()->groupBy('audience');
        $map = fn ($items) => $items->map(fn ($link) => ['label' => $link->label, 'href' => $link->href])->values()->all();

        return ['logo' => ['src' => $this->media->url($row->logo_path), 'alt' => $row->logo_alt, 'href' => $row->logo_href], 'desktopLinks' => $map($links->get('desktop', collect())), 'book' => ['label' => $row->book_label, 'href' => $row->book_href], 'mobile' => ['eyebrow' => $row->mobile_eyebrow, 'openLabel' => $row->mobile_open_label, 'closeLabel' => $row->mobile_close_label, 'links' => $map($links->get('mobile', collect()))]];
    }

    /** @return array<string, mixed>|null */
    public function brandIntroduction(): ?array
    {
        $row = $this->publishedRow('homepage_brand_introductions');
        if (! $row) {
            return null;
        }

        return ['title' => $row->title, 'backgroundWords' => DB::table('homepage_brand_introduction_words')->where('brand_introduction_id', $row->id)->orderBy('slot')->pluck('text')->all(), 'story' => DB::table('homepage_brand_introduction_paragraphs')->where('brand_introduction_id', $row->id)->orderBy('slot')->pluck('body')->all(), 'quote' => $row->quote, 'images' => DB::table('homepage_brand_introduction_images')->where('brand_introduction_id', $row->id)->orderBy('slot')->get()->map(fn ($image) => ['src' => $this->media->url($image->image_path), 'alt' => $image->image_alt])->all()];
    }

    private function publishedRow(string $table): ?\stdClass
    {
        return DB::table($table)->where('status', 'published')->whereNotNull('published_at')->first();
    }
}
