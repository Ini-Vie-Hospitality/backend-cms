<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class WhatsNewSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_journal_sections', "What's New", 'Stories from Bali & Beyond.', 'Thoughtful guides, rituals, places, and discoveries from across Bali.', ['explore_label' => 'View All', 'explore_href' => 'https://inivie.com/blog', 'read_label' => 'Read More']);
        $stories = [
            ['best-restaurants-for-date-night', ['13 Best Restaurants for Date Night in Bali:', 'Romantic Picks 2026'], 'Bali has plenty of romantic dining spots with beautiful views and cozy atmospheres. You can enjoy a sunset dinner on ...', 'https://blog.inivie.com/wp-content/uploads/2026/08/romantic-dinner.jpg'],
            ['recommendations-spa-in-seminyak', ['10 Recommendations Spa in Seminyak:', 'Prices and Signature Menus'], 'The best spa in Seminyak is accessible, as Seminyak has more than 40 spas within walking distance of Jalan Kayu Aya a...', 'https://blog.inivie.com/wp-content/uploads/2026/08/spa-in-seminyak.jpg'],
            ['bali-honeymoon-packages', ['Bali Honeymoon Packages:', 'Best Options for Every Budget 2026'], 'Bali honeymoon packages usually come in options such as 3 days and 2 nights or 5 days and 4 nights. Most packages inc...', 'https://blog.inivie.com/wp-content/uploads/2026/08/honeymoon-package-bali.jpg'],
            ['hair-salons-in-ubud', ['Best Hair Salons in Ubud:', '12 Places for Cuts, Color and Spa'], 'Finding the best hair salon in Ubud is easier than it sounds, since the area is packed with tourist-friendly spots of...', 'https://blog.inivie.com/wp-content/uploads/2026/08/hair-treatment-at-salon.jpg'],
            ['nusa-penida-guide', ['Nusa Penida Guide:', 'Beaches, Boat Times and Trip Costs in Bali'], 'Dewata Island offers many attractions, including Nusa Penida. The island is located in Klungkung Regency, ~20 kilomet...', 'https://blog.inivie.com/wp-content/uploads/2025/11/NUSA-PENIDA.jpg'],
        ];

        foreach ($stories as $index => [$key, $title, $description, $image]) {
            $story = DB::table('homepage_journal_stories')->insertGetId([
                'section_id' => $section,
                'external_key' => $key,
                'category' => 'Discover Bali',
                'description' => $description,
                'reading_time' => '',
                'image_path' => $image,
                'image_alt' => implode(' ', $title),
                'href' => "https://inivie.com/discover-bali/$key",
                'sort_order' => $index,
                ...$published,
            ]);

            foreach ($title as $line => $text) {
                DB::table('homepage_journal_story_title_lines')->insert(['story_id' => $story, 'line_number' => $line + 1, 'text' => $text]);
            }
        }
    }
}
