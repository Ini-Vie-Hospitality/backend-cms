<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class WellnessSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_wellness_sections', 'iNi ViE Wellness', 'Wellness Harmony Escape', 'Find serenity in soulful rituals made to restore.');
        $category = DB::table('homepage_wellness_categories')->insertGetId(['name' => 'Spa', 'slug' => 'spa', 'created_at' => $published['created_at'], 'updated_at' => $published['updated_at']]);
        foreach ([['Svaha Spa Ajowa', 'Seminyak, Bali', 'https://inivie.com/inivie_assets/img/experience/ajowa.avif'], ['Svaha Spa La Mewali', 'Canggu, Bali', 'https://inivie.com/inivie_assets/img/experience/lamewali-v1.webp'], ['Svaha Spa Bisma', 'Ubud, Bali', 'https://inivie.com/inivie_assets/img/experience/bisma.webp']] as $index => [$name, $location, $image]) {
            $escape = DB::table('homepage_wellness_escapes')->insertGetId(['section_id' => $section, 'name' => $name, 'location' => $location, 'description' => 'A restorative sanctuary shaped around touch, stillness, and natural beauty.', 'image_path' => $image, 'image_alt' => $name, 'href' => 'https://svahawellness.com/location', 'cta_label' => 'Discover Experience', 'sort_order' => $index, ...$published]);
            DB::table('homepage_wellness_escape_category')->insert(['wellness_escape_id' => $escape, 'wellness_category_id' => $category, 'sort_order' => 0]);
        }
    }
}
