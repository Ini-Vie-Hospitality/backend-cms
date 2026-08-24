<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class SpecialOffersSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_special_offer_sections', 'Our Special Offers', 'Your Bali escape, thoughtfully elevated.', 'Thoughtfully curated stays, seasonal privileges, and exclusive benefits.', ['all_offers_label' => 'View All', 'all_offers_href' => 'https://inivie.com/offers']);
        $offers = [
            ['01', 'Room Offer', 'INI VIE X TAP CLUB CANGGU', 'Use promo code TAP88 for eligible stays.', 'https://backend.inivie.com/storage/inivie/Offers/6a1bedf2813a897d3fb3304a7.webp', 'ini-vie-x-tap-club-canggu'],
            ['02', 'Room Offer', 'SUMMER DEALS', 'Use promo code MIDSALE for eligible stays.', 'https://backend.inivie.com/storage/inivie/Offers/d1c37d3f5f05115c0d6cb36cd.webp', 'summer-deals'],
            ['03', 'Package', 'PREMIUM HONEYMOON PACKAGE', 'A premium honeymoon package available across selected Ini Vie properties.', 'https://backend.inivie.com/storage/inivie/Offers/50d0f9ddc40c0ca607c91ee7d.webp', 'premium-honeymoon-package'],
        ];

        foreach ($offers as $index => [$number, $category, $title, $description, $image, $slug]) {
            DB::table('homepage_special_offers')->insert([
                'section_id' => $section,
                'slot' => $index + 1,
                'display_number' => $number,
                'category' => $category,
                'title' => $title,
                'description' => $description,
                'image_path' => $image,
                'image_alt' => $title,
                'href' => "https://inivie.com/offers/$slug",
                ...$published,
            ]);
        }
    }
}
