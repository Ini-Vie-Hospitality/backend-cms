<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FeaturedPropertiesSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_featured_property_sections', 'Curated Collection', 'Featured Property For You', 'Exclusive stays designed to make your getaway unforgettable.', ['default_cta_label' => 'Explore Property', 'scroll_label' => 'Scroll to explore']);
        foreach ([['Leedon Villa Seminyak', 'Luxury Villa', 'Elegant villa living with warm tropical design.', '/properties/leedon-villa.webp', '#leedon-villa'], ['Ajowa Resort', 'Resort Experience', 'A refined resort experience blending tropical atmosphere and comfort.', '/properties/ajowa.avif', '#ajowa-resort'], ['La Mewali Resort', 'Resort Experience', 'A considered retreat shaped by lush surroundings.', '/properties/la-mewali.webp', '#la-mewali-resort']] as $index => [$name, $category, $description, $image, $href]) {
            DB::table('homepage_featured_properties')->insert(['section_id' => $section, 'name' => $name, 'category' => $category, 'description' => $description, 'image_path' => $image, 'image_alt' => $name, 'href' => $href, 'cta_label' => 'Explore Property', 'sort_order' => $index, ...$published]);
        }
    }
}
