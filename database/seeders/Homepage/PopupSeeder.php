<?php

namespace Database\Seeders\Homepage;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopupSeeder extends Seeder
{
    private const IMAGE_URL = 'https://inivie.com/voting-popup/pop-iniviehos.webp';

    private const REDIRECT_URL = 'https://exquisite-awards.com/awards/2026/categories/best-unique-concept-hotel-group?nominee=ini-vie-hospitality';

    public function run(): void
    {
        foreach (['published', 'draft'] as $workspace) {
            $workspaceId = DB::table('homepage_workspaces')->where('key', $workspace)->value('id');

            DB::table('homepage_popups')->updateOrInsert(
                ['workspace_id' => $workspaceId],
                [
                    'image_path' => self::IMAGE_URL,
                    'image_alt' => 'Vote for Ini Vie Hospitality in the Exquisite Awards 2026',
                    'redirect_url' => self::REDIRECT_URL,
                    'status' => $workspace === 'published' ? 'published' : 'draft',
                    'published_at' => $workspace === 'published' ? now() : null,
                    'updated_at' => now(),
                ],
            );
        }
    }
}
