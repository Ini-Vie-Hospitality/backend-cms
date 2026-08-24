<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class HomepageContentService
{
    /** @return array<string, array<string, mixed>|null> */
    public function published(): array
    {
        return [
            'navbar' => $this->navbar(),
            'brandIntroduction' => $this->brandIntroduction(),
            'featuredProperties' => $this->collectionSection('homepage_featured_property_sections', 'homepage_featured_properties', fn ($row) => ['id' => $row->id, 'name' => $row->name, 'category' => $row->category, 'description' => $row->description, 'image' => $this->media($row->image_path), 'alt' => $row->image_alt, 'href' => $row->href, 'cta' => $row->cta_label], ['cta' => 'default_cta_label', 'scrollLabel' => 'scroll_label']),
            'culinary' => $this->collectionSection('homepage_culinary_sections', 'homepage_culinary_destinations', fn ($row) => ['name' => $row->name, 'location' => $row->location, 'eyebrow' => $row->eyebrow, 'description' => $row->description, 'schedule' => $row->schedule, 'ctaLabel' => $row->cta_label, 'image' => $this->media($row->image_path), 'alt' => $row->image_alt, 'href' => $row->href], ['scrollLabel' => 'scroll_label']),
            'wellness' => $this->wellness(),
            'membership' => $this->membership(),
            'ourStory' => $this->ourStory(),
            'specialOffers' => $this->specialOffers(),
            'whatsNew' => $this->whatsNew(),
            'featuredIn' => $this->featuredIn(),
            'faq' => $this->collectionSection('homepage_faq_sections', 'homepage_faq_items', fn ($row) => ['question' => $row->question, 'answer' => $row->answer]),
            'footer' => $this->footer(),
        ];
    }

    /** @return array<string, mixed>|null */
    private function navbar(): ?array
    {
        $row = $this->publishedRow('homepage_navbars');
        if (! $row) {
            return null;
        }
        $links = DB::table('homepage_navbar_links')->where('navbar_id', $row->id)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()->groupBy('audience');
        $map = fn ($items) => $items->map(fn ($link) => ['label' => $link->label, 'href' => $link->href])->values()->all();

        return ['logo' => ['src' => $this->media($row->logo_path), 'alt' => $row->logo_alt, 'href' => $row->logo_href], 'desktopLinks' => $map($links->get('desktop', collect())), 'book' => ['label' => $row->book_label, 'href' => $row->book_href], 'mobile' => ['eyebrow' => $row->mobile_eyebrow, 'openLabel' => $row->mobile_open_label, 'closeLabel' => $row->mobile_close_label, 'links' => $map($links->get('mobile', collect()))]];
    }

    /** @return array<string, mixed>|null */
    private function brandIntroduction(): ?array
    {
        $row = $this->publishedRow('homepage_brand_introductions');
        if (! $row) {
            return null;
        }

        return ['title' => $row->title, 'backgroundWords' => DB::table('homepage_brand_introduction_words')->where('brand_introduction_id', $row->id)->orderBy('slot')->pluck('text')->all(), 'story' => DB::table('homepage_brand_introduction_paragraphs')->where('brand_introduction_id', $row->id)->orderBy('slot')->pluck('body')->all(), 'quote' => $row->quote, 'images' => DB::table('homepage_brand_introduction_images')->where('brand_introduction_id', $row->id)->orderBy('slot')->get()->map(fn ($image) => ['src' => $this->media($image->image_path), 'alt' => $image->image_alt])->all()];
    }

    /**
     * @param  callable(\stdClass): array<string, mixed>  $transform
     * @param  array<string, string>  $aliases
     * @return array<string, mixed>|null
     */
    private function collectionSection(string $sectionTable, string $itemTable, callable $transform, array $aliases = []): ?array
    {
        $row = $this->publishedRow($sectionTable);
        if (! $row) {
            return null;
        }
        $result = ['eyebrow' => $row->eyebrow, 'title' => $row->title, 'description' => $row->description];
        foreach ($aliases as $target => $source) {
            $result[$target] = $row->{$source};
        }
        $result['items'] = DB::table($itemTable)->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('sort_order')->orderBy('id')->get()->map($transform)->all();

        return $result;
    }

    /** @return array<string, mixed>|null */
    private function wellness(): ?array
    {
        $result = $this->collectionSection('homepage_wellness_sections', 'homepage_wellness_escapes', function ($row) {
            $categories = DB::table('homepage_wellness_escape_category as pivot')->join('homepage_wellness_categories as categories', 'categories.id', '=', 'pivot.wellness_category_id')->where('pivot.wellness_escape_id', $row->id)->orderBy('pivot.sort_order')->pluck('categories.name')->all();

            return ['id' => $row->id, 'categories' => $categories, 'name' => $row->name, 'location' => $row->location, 'description' => $row->description, 'image' => $this->media($row->image_path), 'alt' => $row->image_alt, 'href' => $row->href, 'cta' => $row->cta_label];
        });

        return $result;
    }

    /** @return array<string, mixed>|null */
    private function membership(): ?array
    {
        $row = $this->publishedRow('homepage_memberships');
        if (! $row) {
            return null;
        }
        $benefits = DB::table('homepage_membership_benefits')->where('membership_id', $row->id)->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => ['label' => $item->label, 'icon' => $item->icon])->all();

        return ['title' => $row->title, 'subtitle' => $row->subtitle, 'description' => $row->description, 'video' => $this->media($row->video_path), 'primary' => ['label' => $row->primary_label, 'href' => $row->primary_href], 'secondary' => ['label' => $row->secondary_label, 'href' => $row->secondary_href], 'benefits' => $benefits];
    }

    /** @return array<string, mixed>|null */
    private function ourStory(): ?array
    {
        $row = $this->publishedRow('homepage_story_sections');
        if (! $row) {
            return null;
        }
        $blocks = DB::table('homepage_story_blocks')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('slot')->get()->map(fn ($item) => ['title' => $item->title, 'description' => $item->description, 'image' => $this->media($item->image_path), 'alt' => $item->image_alt, 'cta' => $item->cta_label, 'href' => $item->href])->all();

        return count($blocks) === 4 ? ['title' => $row->title, 'description' => $row->description, 'blocks' => $blocks] : null;
    }

    /** @return array<string, mixed>|null */
    private function specialOffers(): ?array
    {
        $row = $this->publishedRow('homepage_special_offer_sections');
        if (! $row) {
            return null;
        }
        $items = DB::table('homepage_special_offers')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('slot')->get()->map(fn ($item) => ['id' => $item->display_number, 'category' => $item->category, 'title' => $item->title, 'description' => $item->description, 'image' => $this->media($item->image_path), 'alt' => $item->image_alt, 'href' => $item->href])->all();

        return count($items) === 3 ? ['eyebrow' => $row->eyebrow, 'title' => $row->title, 'description' => $row->description, 'allOffers' => ['label' => $row->all_offers_label, 'href' => $row->all_offers_href], 'items' => $items] : null;
    }

    /** @return array<string, mixed>|null */
    private function whatsNew(): ?array
    {
        $row = $this->publishedRow('homepage_journal_sections');
        if (! $row) {
            return null;
        }
        $items = DB::table('homepage_journal_stories')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('sort_order')->orderBy('id')->get()->map(fn ($item) => ['id' => $item->external_key, 'category' => $item->category, 'title' => DB::table('homepage_journal_story_title_lines')->where('story_id', $item->id)->orderBy('line_number')->pluck('text')->all(), 'description' => $item->description, 'readingTime' => $item->reading_time, 'image' => $this->media($item->image_path), 'alt' => $item->image_alt, 'href' => $item->href])->all();

        return ['eyebrow' => $row->eyebrow, 'title' => $row->title, 'description' => $row->description, 'explore' => ['label' => $row->explore_label, 'href' => $row->explore_href], 'readLabel' => $row->read_label, 'items' => $items];
    }

    /** @return array<string, mixed>|null */
    private function featuredIn(): ?array
    {
        $row = $this->publishedRow('homepage_featured_in_sections');
        if (! $row) {
            return null;
        }

        return ['title' => $row->title, 'items' => DB::table('homepage_featured_in_logos')->where('section_id', $row->id)->where('status', 'published')->whereNotNull('published_at')->orderBy('sort_order')->get()->map(fn ($item) => ['src' => $this->media($item->image_path), 'alt' => $item->image_alt])->all()];
    }

    /** @return array<string, mixed>|null */
    private function footer(): ?array
    {
        $row = $this->publishedRow('homepage_footers');
        if (! $row) {
            return null;
        }
        $contacts = DB::table('homepage_footer_contacts')->where('footer_id', $row->id)->where('is_active', true)->orderBy('sort_order')->get()->map(function ($contact) {
            $data = ['title' => $contact->title, 'actions' => DB::table('homepage_footer_contact_actions')->where('contact_id', $contact->id)->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($action) => ['label' => $action->label, 'href' => $action->href])->all()];
            if ($contact->phone_label) {
                $data['phone'] = ['label' => $contact->phone_label, 'href' => $contact->phone_href];
            } if ($contact->email_label) {
                $data['email'] = ['label' => $contact->email_label, 'href' => $contact->email_href];
            }

            return $data;
        })->all();
        $socials = DB::table('homepage_footer_socials')->where('footer_id', $row->id)->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => ['label' => $item->label, 'href' => $item->href, 'icon' => $item->icon])->all();

        return ['ariaLabel' => $row->aria_label, 'backgroundImage' => $this->media($row->background_image_path), 'logo' => ['src' => $this->media($row->logo_path), 'alt' => $row->logo_alt], 'summary' => $row->summary, 'office' => ['title' => $row->office_title, 'address' => $row->office_address, 'phone' => ['label' => $row->office_phone_label, 'href' => $row->office_phone_href], 'email' => ['label' => $row->office_email_label, 'href' => $row->office_email_href], 'map' => ['label' => $row->office_map_label, 'href' => $row->office_map_href]], 'subscribe' => ['title' => $row->subscribe_title, 'description' => $row->subscribe_description, 'action' => ['label' => $row->subscribe_action_label, 'href' => $row->subscribe_action_href]], 'contacts' => $contacts, 'socialsTitle' => $row->socials_title, 'socials' => $socials, 'policy' => ['label' => $row->policy_label, 'href' => $row->policy_href], 'copyright' => $row->copyright];
    }

    private function publishedRow(string $table): ?\stdClass
    {
        return DB::table($table)->where('status', 'published')->whereNotNull('published_at')->first();
    }

    private function media(?string $path): string
    {
        if (! $path || str_starts_with($path, 'http') || str_starts_with($path, '/')) {
            return $path ?? '';
        }

        return url('/storage/'.$path);
    }
}
