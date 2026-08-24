<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class CulinarySeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_culinary_sections', 'The Culinary Journey', 'A Journey Through Taste.', 'Opening a new chapter in refined dining experience', ['scroll_label' => 'Scroll to explore']);
        foreach ([
            ['Norii Seminyak', 'Seminyak, Bali', 'Japanese', 'Precision, fire, and craftsmanship meet in an intimate dining experience in Seminyak.', 'New Opening — 2026', 'https://inivie.com/inivie_assets/img/culinary/norii-seminyak.avif', '#norii-seminyak'],
            ['Riserva Steakhouse', 'Ubud, Bali', 'Immersive Dining', 'A bold dining experience shaped by premium cuts, open flame, and modern craftsmanship.', 'Dinner · 17:00 — 23:00', 'https://inivie.com/inivie_assets/img/culinary/riserva.webp', '#riserva-steakhouse'],
            ['Terra Verte', 'Ubud, Bali', 'Mediterranean', 'Mediterranean-inspired flavours, shared plates, and relaxed dining shaped for slow moments.', 'New Opening — 2026', 'https://inivie.com/inivie_assets/img/culinary/terraverte.webp', '#terra-verte'],
            ['Habitat Bistro', 'Ubud, Bali', 'Contemporary Bistro', 'A relaxed all-day dining destination pairing familiar flavours with a fresh tropical perspective.', 'Breakfast · 07:00 — 23:00', 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=900&q=85', '#habitat-bistro'],
            ['Shichirin Ubud', 'Ubud, Bali', 'Japanese Teppanyaki', 'Japanese craft, tableside theatre, and live-fire cooking meet in an intimate Ubud setting.', 'Dinner · 17:00 — 23:00', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=85', '#shichirin-ubud'],
            ['Seven Paintings', 'Ubud, Bali', 'Immersive Fine Dining', 'A multi-sensory dinner where storytelling, projection, and refined courses unfold together.', 'Dinner Show · Reservation Only', 'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=900&q=85', '#seven-paintings'],
        ] as $index => [$name, $location, $eyebrow, $description, $schedule, $image, $href]) {
            DB::table('homepage_culinary_destinations')->insert(['section_id' => $section, 'name' => $name, 'location' => $location, 'eyebrow' => $eyebrow, 'description' => $description, 'schedule' => $schedule, 'cta_label' => 'Discover '.$name, 'image_path' => $image, 'image_alt' => $name.' dining experience in '.$location, 'href' => $href, 'sort_order' => $index, ...$published]);
        }
    }
}
