<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FeaturedPropertiesSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_featured_property_sections', 'Curated Collection', 'Featured Property For You', 'Exclusive stays designed to make your getaway unforgettable. Find the place you’ve been dreaming of — and turn every moment into something real.', ['default_cta_label' => 'View All Family', 'scroll_label' => 'Scroll to explore']);
        foreach ([['Leedon Villa Seminyak', 'Luxury Properties', 'Seminyak, Bali', 'https://inivie.com/inivie_assets/img/featured/leedon-villa.webp'], ['Ajowa Resort', 'Resort', 'Seminyak, Bali', 'https://inivie.com/inivie_assets/img/featured/ajowa.avif'], ['La Mewali Resort', 'Resort', 'Canggu, Bali', 'https://inivie.com/inivie_assets/img/featured/lamewali-v1.webp']] as $index => [$name, $category, $description, $image]) {
            DB::table('homepage_featured_properties')->insert(['section_id' => $section, 'name' => $name, 'category' => $category, 'description' => $description, 'image_path' => $image, 'image_alt' => $name, 'href' => 'https://inivie.com/family', 'cta_label' => 'View All Family', 'sort_order' => $index, ...$published]);
        }
    }
}
