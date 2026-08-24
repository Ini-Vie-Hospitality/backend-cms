<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class SpecialOffersSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_special_offer_sections', 'Our Special Offers', 'Your Bali escape, thoughtfully elevated.', 'Thoughtfully curated stays, seasonal privileges, and exclusive benefits.', ['all_offers_label' => 'Explore All Offers', 'all_offers_href' => 'https://inivie.com/offers']);
        foreach ([['01', 'Plan Ahead', 'Early Getaway', '/offers/early-getaway.png'], ['02', 'Curated Privileges', 'Bali Yours', '/offers/bali-yours.png'], ['03', 'Advance Reservations', 'Advance Saver', '/offers/advance-saver.png']] as $index => [$number, $category, $title, $image]) {
            DB::table('homepage_special_offers')->insert(['section_id' => $section, 'slot' => $index + 1, 'display_number' => $number, 'category' => $category, 'title' => $title, 'description' => 'Exclusive privileges created for a memorable Bali escape.', 'image_path' => $image, 'image_alt' => $title, 'href' => 'https://inivie.com/offers/'.str($title)->slug(), ...$published]);
        }
    }
}
