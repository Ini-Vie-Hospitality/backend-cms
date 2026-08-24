<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class OurStorySeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = DB::table('homepage_story_sections')->insertGetId([
            'title' => 'Our Story',
            'description' => 'iNi ViE Hospitality guided by eight mantras that honour people, culture, and nature. Through deeply personalised stays, distinctive resorts and villas, meaningful dining, wellness, and lifestyle experiences, we create memorable journeys across Bali with sustainability at the heart of every decision.',
            ...$published,
        ]);

        $blocks = [
            [
                'About Us',
                'iNi ViE Hospitality manages a growing portfolio of luxury resorts, private pool villas, restaurants, spas, beach clubs, family attractions, and curated experiences in Bali. Our approach combines local culture, contemporary hospitality, and experience-led concepts to create journeys that feel personal, relevant, and worth returning to.',
                'https://inivie.com/inivie_assets/img/our-story/3-v1.webp',
                'https://inivie.com/about',
            ],
            [
                'What Makes Us Different',
                'What makes iNi ViE Hospitality different is our seamless multi-experience journey, combining stays, dining, wellness, culture, leisure, and celebration in one thoughtfully connected guest experience across our portfolio. Supported with distinctive design, personalised service, and consistently high hospitality standards, every touchpoint is designed to feel personal, seamless, and memorable.',
                'https://inivie.com/inivie_assets/img/our-story/1.webp',
                'https://inivie.com/why-choose-us',
            ],
            [
                'Our Eight Mantras',
                'Eight Mantras are the values behind everything we do, inspiring thoughtful hospitality, responsible operations, stronger communities, and meaningful guest experiences across our resorts, villas, restaurants, wellness, and lifestyle destinations.',
                'https://inivie.com/inivie_assets/img/our-story/2.webp',
                'https://inivie.com/mantras',
            ],
            [
                'Sustainability',
                'Sustainability is woven into the way iNi ViE Hospitality operates across Bali. We focus on responsible sourcing, reduced waste, efficient use of water and energy, local employment, community partnerships, and respect for Bali’s natural and cultural heritage, creating hospitality that benefits guests, people, and place.',
                '/our-story/sustainability.jpg',
                'https://inivie.com/sustainability',
            ],
        ];

        foreach ($blocks as $index => [$title, $description, $image, $href]) {
            DB::table('homepage_story_blocks')->insert([
                'section_id' => $section,
                'slot' => $index + 1,
                'title' => $title,
                'description' => $description,
                'image_path' => $image,
                'image_alt' => $title,
                'cta_label' => 'Discover More',
                'href' => $href,
                ...$published,
            ]);
        }
    }
}
