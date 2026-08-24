<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class CulinarySeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_culinary_sections', 'The Culinary Journey', 'A Journey Through Taste.', 'Opening a new chapter in refined dining experience.', ['scroll_label' => 'Scroll to explore']);
        foreach ([['Norii Seminyak', 'Seminyak, Bali', 'Japanese Dining'], ['Riserva Steakhouse', 'Ubud, Bali', 'Open Fire'], ['Terra Verte', 'Ubud, Bali', 'Mediterranean'], ['Habitat Bistro', 'Ubud, Bali', 'Contemporary Bistro']] as $index => [$name, $location, $eyebrow]) {
            DB::table('homepage_culinary_destinations')->insert(['section_id' => $section, 'name' => $name, 'location' => $location, 'eyebrow' => $eyebrow, 'description' => 'A refined dining experience shaped by Bali.', 'schedule' => 'Dinner · 17:00 — 23:00', 'cta_label' => 'Discover '.$name, 'image_path' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=900&q=85', 'image_alt' => $name.' dining experience', 'href' => '#'.str($name)->slug(), 'sort_order' => $index, ...$published]);
        }
    }
}
