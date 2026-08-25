<?php

namespace App\Support;

class CmsCopilot
{
    /** @return array<string, array{title: string, targets: array<string, array{label: string, action: string, action_prefix?: string, fields: array<string, array{label: string, type: string, max_length: int}>}>}> */
    public static function contexts(): array
    {
        $text = fn (string $label, int $max = 255) => ['label' => $label, 'type' => 'text', 'max_length' => $max];
        $longText = fn (string $label, int $max = 10000) => ['label' => $label, 'type' => 'textarea', 'max_length' => $max];
        $url = fn (string $label) => ['label' => $label, 'type' => 'url', 'max_length' => 2048];
        $heading = [
            'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'max_length' => 255],
            'title' => ['label' => 'Title', 'type' => 'text', 'max_length' => 255],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'max_length' => 10000],
        ];

        return [
            'brand-introduction' => ['title' => 'Brand Introduction', 'targets' => [
                'section' => ['label' => 'Brand form', 'action' => '/cms/homepage/brand-introduction', 'fields' => [
                    'title' => $text('Headline'), 'quote' => $longText('Quote'), 'word_1' => $text('Background word 1'), 'word_2' => $text('Background word 2'),
                    'paragraph_1' => $longText('Story paragraph 1'), 'paragraph_2' => $longText('Story paragraph 2'),
                    'image_alt_1' => $text('Image 1 alt text'), 'image_alt_2' => $text('Image 2 alt text'), 'image_alt_3' => $text('Image 3 alt text'),
                ]],
            ]],
            'featured-properties' => ['title' => 'Featured Properties', 'targets' => [
                'section' => ['label' => 'Section settings', 'action' => '/cms/homepage/featured-properties', 'fields' => $heading + ['default_cta_label' => $text('Default CTA'), 'scroll_label' => $text('Scroll label')]],
                'item' => ['label' => 'Property item', 'action_prefix' => '/cms/homepage/featured-properties/items', 'fields' => [
                    'name' => $text('Name'), 'category' => $text('Category'), 'description' => $longText('Description'), 'image_alt' => $text('Image alt text'), 'href' => $url('Redirect URL'), 'cta_label' => $text('CTA label'),
                ]],
            ]],
            'culinary' => ['title' => 'Culinary', 'targets' => [
                'section' => ['label' => 'Section settings', 'action' => '/cms/homepage/culinary', 'fields' => $heading + ['scroll_label' => $text('Scroll label')]],
                'item' => ['label' => 'Dining destination', 'action_prefix' => '/cms/homepage/culinary/items', 'fields' => [
                    'name' => $text('Name'), 'location' => $text('Location'), 'eyebrow' => $text('Category'), 'description' => $longText('Description'), 'schedule' => $text('Schedule'), 'image_alt' => $text('Image alt text'), 'href' => $url('Redirect URL'), 'cta_label' => $text('CTA label'),
                ]],
            ]],
            'wellness' => ['title' => 'Wellness', 'targets' => [
                'section' => ['label' => 'Section settings', 'action' => '/cms/homepage/wellness', 'fields' => $heading],
                'item' => ['label' => 'Wellness escape', 'action_prefix' => '/cms/homepage/wellness/items', 'fields' => [
                    'name' => $text('Name'), 'location' => $text('Location'), 'description' => $longText('Description'), 'image_alt' => $text('Image alt text'), 'href' => $url('Redirect URL'), 'cta_label' => $text('CTA label'),
                ]],
            ]],
            'membership' => ['title' => 'Membership', 'targets' => [
                'section' => ['label' => 'Membership settings', 'action' => '/cms/homepage/membership', 'fields' => [
                    'title' => $text('Title'), 'subtitle' => $text('Subtitle'), 'description' => $longText('Description'), 'primary_label' => $text('Primary CTA label'), 'primary_href' => $url('Primary CTA redirect'), 'secondary_label' => $text('Secondary CTA label'), 'secondary_href' => $url('Secondary CTA redirect'),
                ]],
                'benefit' => ['label' => 'Benefit', 'action_prefix' => '/cms/homepage/membership/benefits', 'fields' => ['label' => $text('Benefit label')]],
            ]],
            'our-story' => ['title' => 'Our Story', 'targets' => [
                'section' => ['label' => 'Our Story section', 'action' => '/cms/homepage/our-story', 'fields' => ['title' => $text('Title'), 'description' => $longText('Description')]],
                'block' => ['label' => 'Story slot', 'action_prefix' => '/cms/homepage/our-story/blocks', 'fields' => [
                    'title' => $text('Title'), 'description' => $longText('Description'), 'image_alt' => $text('Image alt text'), 'cta_label' => $text('CTA label'), 'href' => $url('Redirect URL'),
                ]],
            ]],
            'special-offers' => ['title' => 'Special Offers', 'targets' => [
                'section' => ['label' => 'Section settings', 'action' => '/cms/homepage/special-offers', 'fields' => $heading + ['all_offers_label' => $text('All offers label'), 'all_offers_href' => $url('All offers redirect')]],
                'offer' => ['label' => 'Offer slot', 'action_prefix' => '/cms/homepage/special-offers/items', 'fields' => [
                    'display_number' => $text('Display number', 10), 'category' => $text('Category'), 'title' => $text('Title'), 'description' => $longText('Description'), 'image_alt' => $text('Image alt text'), 'href' => $url('Redirect URL'),
                ]],
            ]],
            'whats-new' => ['title' => "What's New", 'targets' => [
                'section' => ['label' => 'Section settings', 'action' => '/cms/homepage/whats-new', 'fields' => $heading + ['explore_label' => $text('Explore label'), 'explore_href' => $url('Explore redirect'), 'read_label' => $text('Read label')]],
                'item' => ['label' => 'Journal story', 'action_prefix' => '/cms/homepage/whats-new/items', 'fields' => [
                    'external_key' => $text('Slug'), 'category' => $text('Category'), 'description' => $longText('Description'), 'reading_time' => $text('Reading time'), 'image_alt' => $text('Image alt text'), 'href' => $url('Redirect URL'),
                ]],
            ]],
            'faq' => ['title' => 'FAQ', 'targets' => [
                'section' => ['label' => 'FAQ settings', 'action' => '/cms/homepage/faq', 'fields' => $heading],
                'item' => ['label' => 'FAQ item', 'action_prefix' => '/cms/homepage/faq/items', 'fields' => ['question' => $text('Question'), 'answer' => $longText('Answer')]],
            ]],
        ];
    }

    public static function context(string $key): array
    {
        return self::contexts()[$key] ?? abort(404);
    }

    public static function target(string $contextKey, string $targetKey): array
    {
        $context = self::context($contextKey);

        return $context['targets'][$targetKey] ?? abort(404, 'Copilot form target not found');
    }

    public static function validateTargetAction(array $target, string $action): bool
    {
        if (isset($target['action'])) {
            return rtrim($action, '/') === rtrim($target['action'], '/');
        }

        $prefix = rtrim((string) $target['action_prefix'], '/');
        $normalizedName = rtrim($action, '/');

        return $normalizedName === $prefix || str_starts_with($normalizedName, $prefix.'/');
    }

    /** @return array{context:string,title:string,targets:list<array<string,mixed>>}|null */
    public static function contextForPath(string $path): ?array
    {
        $segments = explode('/', trim($path, '/'));

        if (count($segments) < 3 || $segments[0] !== 'cms' || $segments[1] !== 'homepage') {
            return null;
        }

        $key = $segments[2];

        if (in_array($key, ['navbar', 'footer', 'preview', 'history', 'featured-in'], true)) {
            return null;
        }

        if (! array_key_exists($key, self::contexts())) {
            return null;
        }

        $context = self::contexts()[$key];

        return [
            'context' => $key,
            'title' => $context['title'],
            'targets' => collect($context['targets'])->map(fn (array $target, string $targetKey) => [
                'key' => $targetKey,
                'label' => $target['label'],
                'action' => $target['action'] ?? null,
                'action_prefix' => $target['action_prefix'] ?? null,
                'fields' => $target['fields'],
            ])->values()->all(),
        ];
    }
}
