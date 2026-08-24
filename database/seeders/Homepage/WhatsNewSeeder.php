<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class WhatsNewSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_journal_sections', "What's New", 'Stories from Bali & Beyond.', 'Thoughtful guides, rituals, places, and discoveries from across Bali.', ['explore_label' => 'Explore The Story', 'explore_href' => '#journal-nusa-penida', 'read_label' => 'Read Story']);
        foreach ([['nusa-penida', 'Destination', 'Nusa Penida,', 'Beyond the Postcard'], ['quiet-art', 'Wellness · Ubud', 'The Quiet Art', 'of Slowing Down'], ['sacred-places', 'Bali Culture', 'Sacred Places,', 'Timeless Traditions'], ['september-guide', 'Seasonal Guide', 'Bali in September:', 'A Guide to the Season']] as $index => [$key, $category, $lineOne, $lineTwo]) {
            $story = DB::table('homepage_journal_stories')->insertGetId(['section_id' => $section, 'external_key' => $key, 'category' => $category, 'description' => 'Discover meaningful stories and places across Bali.', 'reading_time' => '5 min read', 'image_path' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1600&q=85', 'image_alt' => $lineOne, 'href' => '#journal-'.$key, 'sort_order' => $index, ...$published]);
            foreach ([$lineOne, $lineTwo] as $line => $text) {
                DB::table('homepage_journal_story_title_lines')->insert(['story_id' => $story, 'line_number' => $line + 1, 'text' => $text]);
            }
        }
    }
}
