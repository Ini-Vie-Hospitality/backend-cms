<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class OurStorySeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = DB::table('homepage_story_sections')->insertGetId(['title' => 'Our Story', 'description' => 'iNi ViE Hospitality guided by eight mantras that honour people, culture, and nature.', ...$published]);
        foreach ([['About Us', '/our-story/infinity-pool.png'], ['What Makes Us Different', '/our-story/meaningful-journey.png'], ['Our Eight Mantras', '/our-story/eight-mantras.jpg'], ['Sustainability', '/our-story/sustainability.jpg']] as $index => [$title, $image]) {
            DB::table('homepage_story_blocks')->insert(['section_id' => $section, 'slot' => $index + 1, 'title' => $title, 'description' => 'Discover the values and meaningful experiences that shape iNi ViE Hospitality.', 'image_path' => $image, 'image_alt' => $title, 'cta_label' => $index ? 'Discover More' : 'Explore Our Story', 'href' => 'https://inivie.com/about', ...$published]);
        }
    }
}
