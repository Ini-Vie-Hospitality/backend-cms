<?php

namespace App\Http\Controllers;

use App\Services\HomepageMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RelationalHomepageController extends Controller
{
    public function navbar(): Response
    {
        return $this->page('navbar/edit', 'homepage_navbars', ['links' => DB::table('homepage_navbar_links')->orderBy('audience')->orderBy('sort_order')->get()]);
    }

    public function brandIntroduction(): Response
    {
        $record = DB::table('homepage_brand_introductions')->first();
        $words = DB::table('homepage_brand_introduction_words')->orderBy('slot')->pluck('text')->all();
        $paragraphs = DB::table('homepage_brand_introduction_paragraphs')->orderBy('slot')->pluck('body')->all();
        $images = DB::table('homepage_brand_introduction_images')->orderBy('slot')->get();

        return Inertia::render('homepage/brand-introduction/edit', ['record' => [...get_object_vars($record), 'word_1' => $words[0] ?? '', 'word_2' => $words[1] ?? '', 'paragraph_1' => $paragraphs[0] ?? '', 'paragraph_2' => $paragraphs[1] ?? '', 'image_alt_1' => $images[0]->image_alt ?? '', 'image_alt_2' => $images[1]->image_alt ?? '', 'image_alt_3' => $images[2]->image_alt ?? '']]);
    }

    public function featuredProperties(): Response
    {
        return $this->page('featured-properties/index', 'homepage_featured_property_sections', ['items' => DB::table('homepage_featured_properties')->orderBy('sort_order')->get()]);
    }

    public function culinary(): Response
    {
        return $this->page('culinary/index', 'homepage_culinary_sections', ['items' => DB::table('homepage_culinary_destinations')->orderBy('sort_order')->get()]);
    }

    public function wellness(): Response
    {
        return $this->page('wellness/index', 'homepage_wellness_sections', ['items' => DB::table('homepage_wellness_escapes')->orderBy('sort_order')->get(), 'categories' => DB::table('homepage_wellness_categories')->orderBy('name')->get()]);
    }

    public function membership(): Response
    {
        return $this->page('membership/edit', 'homepage_memberships', ['benefits' => DB::table('homepage_membership_benefits')->orderBy('sort_order')->get()]);
    }

    public function ourStory(): Response
    {
        return $this->page('our-story/edit', 'homepage_story_sections', ['blocks' => DB::table('homepage_story_blocks')->orderBy('slot')->get()]);
    }

    public function specialOffers(): Response
    {
        return $this->page('special-offers/edit', 'homepage_special_offer_sections', ['items' => DB::table('homepage_special_offers')->orderBy('slot')->get()]);
    }

    public function whatsNew(): Response
    {
        return $this->page('whats-new/index', 'homepage_journal_sections', ['items' => DB::table('homepage_journal_stories')->orderBy('sort_order')->get()]);
    }

    public function featuredIn(): Response
    {
        return $this->page('featured-in/index', 'homepage_featured_in_sections', ['items' => DB::table('homepage_featured_in_logos')->orderBy('sort_order')->get()]);
    }

    public function faq(): Response
    {
        return $this->page('faq/index', 'homepage_faq_sections', ['items' => DB::table('homepage_faq_items')->orderBy('sort_order')->get()]);
    }

    public function footer(): Response
    {
        return $this->page('footer/edit', 'homepage_footers', ['contacts' => DB::table('homepage_footer_contacts')->orderBy('sort_order')->get(), 'socials' => DB::table('homepage_footer_socials')->orderBy('sort_order')->get()]);
    }

    public function updateBrandIntroduction(Request $request, HomepageMediaService $media): RedirectResponse
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'quote' => 'required|string|max:10000', 'status' => 'required|in:draft,published', 'word_1' => 'required|string|max:255', 'word_2' => 'required|string|max:255', 'paragraph_1' => 'required|string|max:10000', 'paragraph_2' => 'required|string|max:10000', 'image_1' => 'nullable|image|max:10240', 'image_2' => 'nullable|image|max:10240', 'image_3' => 'nullable|image|max:10240', 'image_alt_1' => 'required|string|max:255', 'image_alt_2' => 'required|string|max:255', 'image_alt_3' => 'required|string|max:255']);
        $data['words'] = [$data['word_1'], $data['word_2']];
        $data['paragraphs'] = [$data['paragraph_1'], $data['paragraph_2']];
        $row = DB::table('homepage_brand_introductions')->firstOrFail();
        DB::transaction(function () use ($data, $row, $request, $media) {
            DB::table('homepage_brand_introductions')->where('id', $row->id)->update(['title' => $data['title'], 'quote' => $data['quote'], 'status' => $data['status'], 'published_at' => $data['status'] === 'published' ? now() : null]);
            foreach ($data['words'] as $slot => $text) {
                DB::table('homepage_brand_introduction_words')->where(['brand_introduction_id' => $row->id, 'slot' => $slot + 1])->update(['text' => $text]);
            }
            foreach ($data['paragraphs'] as $slot => $body) {
                DB::table('homepage_brand_introduction_paragraphs')->where(['brand_introduction_id' => $row->id, 'slot' => $slot + 1])->update(['body' => $body]);
            }
            for ($slot = 1; $slot <= 3; $slot++) {
                $image = ['image_alt' => $data["image_alt_$slot"]];
                if ($request->hasFile("image_$slot")) {
                    $image['image_path'] = $media->store($request->file("image_$slot"));
                }
                DB::table('homepage_brand_introduction_images')->where(['brand_introduction_id' => $row->id, 'slot' => $slot])->update($image);
            }
        });

        return back()->with('success', 'Brand introduction saved.');
    }

    public function saveNavbarLink(Request $request, ?int $item = null): RedirectResponse
    {
        $data = $request->validate(['audience' => 'required|in:desktop,mobile', 'label' => 'required|string|max:255', 'href' => 'required|string|max:2048', 'sort_order' => 'required|integer|min:0', 'is_active' => 'required|boolean']);
        if ($item) {
            DB::table('homepage_navbar_links')->where('id', $item)->update($data);
        } else {
            $data['navbar_id'] = DB::table('homepage_navbars')->value('id');
            DB::table('homepage_navbar_links')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        }

        return back()->with('success', 'Navigation link saved.');
    }

    public function deleteNavbarLink(int $item): RedirectResponse
    {
        DB::table('homepage_navbar_links')->where('id', $item)->delete();

        return back();
    }

    public function saveBenefit(Request $request, ?int $item = null): RedirectResponse
    {
        $data = $request->validate(['label' => 'required|string|max:255', 'icon' => 'required|in:diamond,gift,shopping-bag,tags', 'sort_order' => 'required|integer|min:0', 'is_active' => 'required|boolean']);
        if ($item) {
            DB::table('homepage_membership_benefits')->where('id', $item)->update($data);
        } else {
            $data['membership_id'] = DB::table('homepage_memberships')->value('id');
            DB::table('homepage_membership_benefits')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        }

        return back();
    }

    public function deleteBenefit(int $item): RedirectResponse
    {
        DB::table('homepage_membership_benefits')->where('id', $item)->delete();

        return back();
    }

    public function updateStoryBlock(Request $request, int $item, HomepageMediaService $media): RedirectResponse
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string|max:10000', 'image_alt' => 'required|string|max:255', 'cta_label' => 'required|string|max:255', 'href' => 'required|string|max:2048', 'status' => 'required|in:draft,published', 'image' => 'nullable|image|max:10240']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $media->store($request->file('image'));
        }unset($data['image']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table('homepage_story_blocks')->where('id', $item)->update($data);

        return back();
    }

    public function updateOffer(Request $request, int $item, HomepageMediaService $media): RedirectResponse
    {
        $data = $request->validate(['display_number' => 'required|string|max:10', 'category' => 'required|string|max:255', 'title' => 'required|string|max:255', 'description' => 'required|string|max:10000', 'image_alt' => 'required|string|max:255', 'href' => 'required|string|max:2048', 'status' => 'required|in:draft,published', 'image' => 'nullable|image|max:10240']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $media->store($request->file('image'));
        }unset($data['image']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table('homepage_special_offers')->where('id', $item)->update($data);

        return back();
    }

    public function saveFooterContact(Request $request, ?int $item = null): RedirectResponse
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'phone_label' => 'nullable|string|max:255', 'phone_href' => 'nullable|string|max:2048', 'email_label' => 'nullable|email|max:255', 'email_href' => 'nullable|string|max:2048', 'sort_order' => 'required|integer|min:0', 'is_active' => 'required|boolean']);
        if ($item) {
            DB::table('homepage_footer_contacts')->where('id', $item)->update($data);
        } else {
            $data['footer_id'] = DB::table('homepage_footers')->value('id');
            DB::table('homepage_footer_contacts')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        }

        return back();
    }

    public function deleteFooterContact(int $item): RedirectResponse
    {
        DB::table('homepage_footer_contacts')->where('id', $item)->delete();

        return back();
    }

    public function saveFooterSocial(Request $request, ?int $item = null): RedirectResponse
    {
        $data = $request->validate(['label' => 'required|string|max:255', 'href' => 'required|string|max:2048', 'icon' => 'required|in:facebook,instagram,linkedin,youtube,tiktok', 'sort_order' => 'required|integer|min:0', 'is_active' => 'required|boolean']);
        if ($item) {
            DB::table('homepage_footer_socials')->where('id', $item)->update($data);
        } else {
            $data['footer_id'] = DB::table('homepage_footers')->value('id');
            DB::table('homepage_footer_socials')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        }

        return back();
    }

    public function deleteFooterSocial(int $item): RedirectResponse
    {
        DB::table('homepage_footer_socials')->where('id', $item)->delete();

        return back();
    }

    public function updateNavbar(Request $request, HomepageMediaService $media): RedirectResponse
    {
        $data = $request->validate(['logo_alt' => 'required|string|max:255', 'logo_href' => 'required|string|max:2048', 'book_label' => 'required|string|max:255', 'book_href' => 'required|string|max:2048', 'mobile_eyebrow' => 'required|string|max:255', 'mobile_open_label' => 'required|string|max:255', 'mobile_close_label' => 'required|string|max:255', 'status' => 'required|in:draft,published', 'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:10240']);
        $row = DB::table('homepage_navbars')->first();
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $media->store($request->file('logo'));
        } unset($data['logo']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table('homepage_navbars')->where('id', $row->id)->update($data);

        return back()->with('success', 'Navbar saved.');
    }

    public function updateSection(Request $request, string $section, HomepageMediaService $media): RedirectResponse
    {
        $definitions = $this->sectionDefinitions();
        abort_unless(isset($definitions[$section]), 404);
        [$table,$fields,$mediaFields] = $definitions[$section];
        $rules = ['status' => 'required|in:draft,published'];
        foreach ($fields as $field => $type) {
            $rules[$field] = ['required', $type === 'text' ? 'string' : 'string', 'max:'.($type === 'text' ? 10000 : 2048)];
        } foreach ($mediaFields as $field => $kind) {
            $rules[$field] = [$field === 'video' ? 'nullable' : 'nullable', 'file', $kind === 'video' ? 'mimetypes:video/mp4,video/webm' : 'mimes:jpg,jpeg,png,webp,avif', 'max:'.($kind === 'video' ? 51200 : 10240)];
        }
        $data = $request->validate($rules);
        $row = DB::table($table)->firstOrFail();
        foreach ($mediaFields as $field => $kind) {
            if ($request->hasFile($field)) {
                $column = $field.'_path';
                $data[$column] = $media->store($request->file($field));
            }
        } foreach (array_keys($mediaFields) as $field) {
            unset($data[$field]);
        } $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table($table)->where('id', $row->id)->update($data);

        return back()->with('success', 'Section saved.');
    }

    public function storeItem(Request $request, string $section, HomepageMediaService $media): RedirectResponse
    {
        return $this->persistItem($request, $section, $media, null);
    }

    public function updateItem(Request $request, int $item, string $section, HomepageMediaService $media): RedirectResponse
    {
        return $this->persistItem($request, $section, $media, $item);
    }

    private function persistItem(Request $request, string $section, HomepageMediaService $media, ?int $item): RedirectResponse
    {
        $definitions = $this->itemDefinitions();
        abort_unless(isset($definitions[$section]), 404);
        [$table,$parentTable,$fields,$hasImage] = $definitions[$section];
        $rules = ['sort_order' => 'required|integer|min:0', 'status' => 'required|in:draft,published'];
        foreach ($fields as $field => $type) {
            $rules[$field] = [$type === 'nullable' ? 'nullable' : 'required', 'string', 'max:'.($type === 'text' ? 10000 : 2048)];
        } if ($hasImage) {
            $rules['image'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'];
        } $data = $request->validate($rules);
        if ($hasImage && $request->hasFile('image')) {
            $data['image_path'] = $media->store($request->file('image'));
        } unset($data['image']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        if ($item) {
            DB::table($table)->where('id', $item)->update($data);
        } else {
            $parent = DB::table($parentTable)->firstOrFail();
            $data['section_id'] = $parent->id;
            DB::table($table)->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        }

        return back()->with('success', 'Item saved.');
    }

    public function createItem(string $section): Response
    {
        abort_unless(isset($this->itemDefinitions()[$section]), 404);

        return Inertia::render("homepage/$section/create");
    }

    public function editItem(string $section, int $item): Response
    {
        $table = $this->itemDefinitions()[$section][0] ?? abort(404);

        return Inertia::render("homepage/$section/edit", ['item' => DB::table($table)->find($item) ?? abort(404)]);
    }

    public function deleteItem(int $item, string $section): RedirectResponse
    {
        $table = $this->itemDefinitions()[$section][0] ?? abort(404);
        DB::table($table)->where('id', $item)->delete();

        return back()->with('success', 'Item deleted.');
    }

    /** @param array<string, mixed> $props */
    private function page(string $component, string $table, array $props = []): Response
    {
        return Inertia::render('homepage/'.$component, ['record' => DB::table($table)->first(), ...$props]);
    }

    /** @return array<string, array{string, array<string, string>, array<string, string>}> */
    private function sectionDefinitions(): array
    {
        $heading = ['eyebrow' => 'string', 'title' => 'string', 'description' => 'text'];

        return ['featured-properties' => ['homepage_featured_property_sections', $heading + ['default_cta_label' => 'string', 'scroll_label' => 'string'], []], 'culinary' => ['homepage_culinary_sections', $heading + ['scroll_label' => 'string'], []], 'wellness' => ['homepage_wellness_sections', $heading, []], 'membership' => ['homepage_memberships', ['title' => 'string', 'subtitle' => 'string', 'description' => 'text', 'primary_label' => 'string', 'primary_href' => 'string', 'secondary_label' => 'string', 'secondary_href' => 'string'], ['video' => 'video']], 'our-story' => ['homepage_story_sections', ['title' => 'string', 'description' => 'text'], []], 'special-offers' => ['homepage_special_offer_sections', $heading + ['all_offers_label' => 'string', 'all_offers_href' => 'string'], []], 'whats-new' => ['homepage_journal_sections', $heading + ['explore_label' => 'string', 'explore_href' => 'string', 'read_label' => 'string'], []], 'featured-in' => ['homepage_featured_in_sections', ['title' => 'string'], []], 'faq' => ['homepage_faq_sections', $heading, []], 'footer' => ['homepage_footers', ['aria_label' => 'string', 'logo_alt' => 'string', 'summary' => 'text', 'office_title' => 'string', 'office_address' => 'text', 'office_phone_label' => 'string', 'office_phone_href' => 'string', 'office_email_label' => 'string', 'office_email_href' => 'string', 'office_map_label' => 'string', 'office_map_href' => 'string', 'subscribe_title' => 'string', 'subscribe_description' => 'text', 'subscribe_action_label' => 'string', 'subscribe_action_href' => 'string', 'socials_title' => 'string', 'policy_label' => 'string', 'policy_href' => 'string', 'copyright' => 'string'], ['background_image' => 'image', 'logo' => 'image']]];
    }

    /** @return array<string, array{string, string, array<string, string>, bool}> */
    private function itemDefinitions(): array
    {
        return ['featured-properties' => ['homepage_featured_properties', 'homepage_featured_property_sections', ['name' => 'string', 'category' => 'string', 'description' => 'text', 'image_alt' => 'string', 'href' => 'string', 'cta_label' => 'string'], true], 'culinary' => ['homepage_culinary_destinations', 'homepage_culinary_sections', ['name' => 'string', 'location' => 'string', 'eyebrow' => 'string', 'description' => 'text', 'schedule' => 'string', 'cta_label' => 'string', 'image_alt' => 'string', 'href' => 'string'], true], 'wellness' => ['homepage_wellness_escapes', 'homepage_wellness_sections', ['name' => 'string', 'location' => 'string', 'description' => 'text', 'image_alt' => 'string', 'href' => 'string', 'cta_label' => 'string'], true], 'whats-new' => ['homepage_journal_stories', 'homepage_journal_sections', ['external_key' => 'string', 'category' => 'string', 'description' => 'text', 'reading_time' => 'string', 'image_alt' => 'string', 'href' => 'string'], true], 'featured-in' => ['homepage_featured_in_logos', 'homepage_featured_in_sections', ['image_alt' => 'string'], true], 'faq' => ['homepage_faq_items', 'homepage_faq_sections', ['question' => 'string', 'answer' => 'text'], false]];
    }
}
