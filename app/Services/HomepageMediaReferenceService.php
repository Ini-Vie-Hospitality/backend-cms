<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class HomepageMediaReferenceService
{
    private const REFERENCES = [
        ['homepage_navbars', 'logo_path'],
        ['homepage_brand_introduction_images', 'image_path'],
        ['homepage_featured_properties', 'image_path'],
        ['homepage_culinary_destinations', 'image_path'],
        ['homepage_wellness_escapes', 'image_path'],
        ['homepage_memberships', 'video_path'],
        ['homepage_story_blocks', 'image_path'],
        ['homepage_special_offers', 'image_path'],
        ['homepage_journal_stories', 'image_path'],
        ['homepage_featured_in_logos', 'image_path'],
        ['homepage_footers', 'background_image_path'],
        ['homepage_footers', 'logo_path'],
    ];

    public function exists(string $path): bool
    {
        foreach (self::REFERENCES as [$table, $column]) {
            if (DB::table($table)->where($column, $path)->exists()) {
                return true;
            }
        }

        return false;
    }
}
