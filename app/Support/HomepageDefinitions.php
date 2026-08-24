<?php

namespace App\Support;

final class HomepageDefinitions
{
    /** @return array<string, array{table: string, component: string, fields: array<string, string>, media: array<string, string>}> */
    public static function sections(): array
    {
        $heading = ['eyebrow' => 'string', 'title' => 'string', 'description' => 'text'];

        return [
            'featured-properties' => ['table' => 'homepage_featured_property_sections', 'component' => 'featured-properties/index', 'fields' => $heading + ['default_cta_label' => 'string', 'scroll_label' => 'string'], 'media' => []],
            'culinary' => ['table' => 'homepage_culinary_sections', 'component' => 'culinary/index', 'fields' => $heading + ['scroll_label' => 'string'], 'media' => []],
            'wellness' => ['table' => 'homepage_wellness_sections', 'component' => 'wellness/index', 'fields' => $heading, 'media' => []],
            'membership' => ['table' => 'homepage_memberships', 'component' => 'membership/edit', 'fields' => ['title' => 'string', 'subtitle' => 'string', 'description' => 'text', 'primary_label' => 'string', 'primary_href' => 'string', 'secondary_label' => 'string', 'secondary_href' => 'string'], 'media' => ['video' => 'video']],
            'our-story' => ['table' => 'homepage_story_sections', 'component' => 'our-story/edit', 'fields' => ['title' => 'string', 'description' => 'text'], 'media' => []],
            'special-offers' => ['table' => 'homepage_special_offer_sections', 'component' => 'special-offers/edit', 'fields' => $heading + ['all_offers_label' => 'string', 'all_offers_href' => 'string'], 'media' => []],
            'whats-new' => ['table' => 'homepage_journal_sections', 'component' => 'whats-new/index', 'fields' => $heading + ['explore_label' => 'string', 'explore_href' => 'string', 'read_label' => 'string'], 'media' => []],
            'featured-in' => ['table' => 'homepage_featured_in_sections', 'component' => 'featured-in/index', 'fields' => ['title' => 'string'], 'media' => []],
            'faq' => ['table' => 'homepage_faq_sections', 'component' => 'faq/index', 'fields' => $heading, 'media' => []],
            'footer' => ['table' => 'homepage_footers', 'component' => 'footer/edit', 'fields' => ['aria_label' => 'string', 'logo_alt' => 'string', 'summary' => 'text', 'office_title' => 'string', 'office_address' => 'text', 'office_phone_label' => 'string', 'office_phone_href' => 'string', 'office_email_label' => 'string', 'office_email_href' => 'string', 'office_map_label' => 'string', 'office_map_href' => 'string', 'subscribe_title' => 'string', 'subscribe_description' => 'text', 'subscribe_action_label' => 'string', 'subscribe_action_href' => 'string', 'socials_title' => 'string', 'policy_label' => 'string', 'policy_href' => 'string', 'copyright' => 'string'], 'media' => ['background_image' => 'image', 'logo' => 'image']],
        ];
    }

    /** @return array<string, array{table: string, parent: string, fields: array<string, string>, image: bool}> */
    public static function items(): array
    {
        return [
            'featured-properties' => ['table' => 'homepage_featured_properties', 'parent' => 'homepage_featured_property_sections', 'fields' => ['name' => 'string', 'category' => 'string', 'description' => 'text', 'image_alt' => 'string', 'href' => 'string', 'cta_label' => 'string'], 'image' => true],
            'culinary' => ['table' => 'homepage_culinary_destinations', 'parent' => 'homepage_culinary_sections', 'fields' => ['name' => 'string', 'location' => 'string', 'eyebrow' => 'string', 'description' => 'text', 'schedule' => 'string', 'cta_label' => 'string', 'image_alt' => 'string', 'href' => 'string'], 'image' => true],
            'wellness' => ['table' => 'homepage_wellness_escapes', 'parent' => 'homepage_wellness_sections', 'fields' => ['name' => 'string', 'location' => 'string', 'description' => 'text', 'image_alt' => 'string', 'href' => 'string', 'cta_label' => 'string'], 'image' => true],
            'whats-new' => ['table' => 'homepage_journal_stories', 'parent' => 'homepage_journal_sections', 'fields' => ['external_key' => 'string', 'category' => 'string', 'description' => 'text', 'reading_time' => 'string', 'image_alt' => 'string', 'href' => 'string'], 'image' => true],
            'featured-in' => ['table' => 'homepage_featured_in_logos', 'parent' => 'homepage_featured_in_sections', 'fields' => ['image_alt' => 'string'], 'image' => true],
            'faq' => ['table' => 'homepage_faq_items', 'parent' => 'homepage_faq_sections', 'fields' => ['question' => 'string', 'answer' => 'text'], 'image' => false],
        ];
    }

    /** @return array{table: string, component: string, fields: array<string, string>, media: array<string, string>} */
    public static function section(string $key): array
    {
        return self::sections()[$key] ?? abort(404);
    }

    /** @return array{table: string, parent: string, fields: array<string, string>, image: bool} */
    public static function item(string $key): array
    {
        return self::items()[$key] ?? abort(404);
    }
}
