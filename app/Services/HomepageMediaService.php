<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomepageMediaService
{
    public function store(UploadedFile $file): string
    {
        $path = $file->store('homepage', 'public');
        if ($path === false) {
            throw new \RuntimeException('Unable to store homepage media.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if (! $this->isManaged($path) || $this->isReferenced($path)) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    private function isManaged(?string $path): bool
    {
        return is_string($path) && preg_match('#^homepage/[A-Za-z0-9][A-Za-z0-9._/-]*$#', $path) === 1 && ! str_contains($path, '..') && ! str_contains($path, '//');
    }

    private function isReferenced(string $path): bool
    {
        foreach ([
            ['homepage_navbars', 'logo_path'], ['homepage_brand_introduction_images', 'image_path'],
            ['homepage_featured_properties', 'image_path'], ['homepage_culinary_destinations', 'image_path'],
            ['homepage_wellness_escapes', 'image_path'], ['homepage_memberships', 'video_path'],
            ['homepage_story_blocks', 'image_path'], ['homepage_special_offers', 'image_path'],
            ['homepage_journal_stories', 'image_path'], ['homepage_featured_in_logos', 'image_path'],
            ['homepage_footers', 'background_image_path'], ['homepage_footers', 'logo_path'],
        ] as [$table, $column]) {
            if (DB::table($table)->where($column, $path)->exists()) {
                return true;
            }
        }

        return false;
    }
}
