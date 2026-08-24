<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class WellnessSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_wellness_sections', 'iNi ViE Wellness', 'Wellness Harmony Escape', 'Find serenity in soulful rituals made to restore.');
        foreach ([['Svaha Spa Ajowa', 'Seminyak, Bali'], ['Svaha Spa La Mewali', 'Canggu, Bali'], ['Svaha Spa Bisma', 'Ubud, Bali'], ['Svaha Wellness', 'Nusa Dua, Bali']] as $index => [$name, $location]) {
            DB::table('homepage_wellness_escapes')->insert(['section_id' => $section, 'name' => $name, 'location' => $location, 'description' => 'A restorative sanctuary shaped around touch, stillness, and natural beauty.', 'image_path' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1600&q=88', 'image_alt' => $name.' wellness experience', 'href' => '#'.str($name)->slug(), 'cta_label' => 'Discover Experience', 'sort_order' => $index, ...$published]);
        }
    }
}
