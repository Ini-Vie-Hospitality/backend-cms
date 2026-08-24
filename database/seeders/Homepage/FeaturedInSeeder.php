<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FeaturedInSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = DB::table('homepage_featured_in_sections')->insertGetId(['title' => 'Featured In', ...$published]);
        foreach ([1, 2, 3, 4, 5, 6, 7, 9, 10] as $index => $number) {
            DB::table('homepage_featured_in_logos')->insert(['section_id' => $section, 'image_path' => "https://inivie.com/inivie_assets/img/logomedia/$number.png", 'image_alt' => 'Featured publication '.($index + 1), 'sort_order' => $index, ...$published]);
        }
    }
}
