<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class BrandIntroductionSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $brand = DB::table('homepage_brand_introductions')->insertGetId(['title' => 'iNi ViE Hospitality', 'quote' => 'Rooted in Bali — made for meaningful journeys.', ...$this->published()]);
        foreach (['BALI', 'INI VIE'] as $index => $text) {
            DB::table('homepage_brand_introduction_words')->insert(['brand_introduction_id' => $brand, 'slot' => $index + 1, 'text' => $text]);
        }
        foreach (['iNi ViE Hospitality is a Bali-based hospitality group creating meaningful stays, dining destinations, wellness experiences, and lifestyle concepts across the island.', 'Each property reflects the character of its destination through thoughtful design and warm service — creating a distinctive way to experience Bali where every moment feels personal.'] as $index => $body) {
            DB::table('homepage_brand_introduction_paragraphs')->insert(['brand_introduction_id' => $brand, 'slot' => $index + 1, 'body' => $body]);
        }
        foreach ([['/brand-introduction/villa-desktop.webp', 'A tropical Bali villa surrounded by lush gardens'], ['/brand-introduction/breakfast-desktop.webp', 'A guest enjoying breakfast beside a tropical pool'], ['/brand-introduction/offering-desktop.webp', 'A traditional Balinese floral offering']] as $index => [$path, $alt]) {
            DB::table('homepage_brand_introduction_images')->insert(['brand_introduction_id' => $brand, 'slot' => $index + 1, 'image_path' => $path, 'image_alt' => $alt]);
        }
    }
}
