<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class NavbarSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $navbar = DB::table('homepage_navbars')->insertGetId(['logo_path' => '/inivie-white.png', 'logo_alt' => 'Ini Vie Hospitality', 'logo_href' => '/', 'book_label' => 'Book Your Stay', 'book_href' => '#booking', 'mobile_eyebrow' => 'Explore Ini Vie', 'mobile_open_label' => 'Open menu', 'mobile_close_label' => 'Close menu', ...$published]);
        foreach ([['desktop', 'Stays', '#stays'], ['desktop', 'Dining', '#dining'], ['desktop', 'Wellness', '#wellness'], ['desktop', 'Membership', '#membership'], ['desktop', 'Offers', '#offers'], ['mobile', 'About', '#about'], ['mobile', 'Stays', '#stays'], ['mobile', 'Dining', '#dining'], ['mobile', 'Wellness', '#wellness'], ['mobile', 'Membership', '#membership'], ['mobile', 'Our Story', '#our-story'], ['mobile', 'Special Offers', '#offers'], ['mobile', "What's New", '#journal'], ['mobile', 'Featured In', '#featured-in'], ['mobile', 'FAQ', '#faq']] as $order => [$audience, $label, $href]) {
            DB::table('homepage_navbar_links')->insert(['navbar_id' => $navbar, 'audience' => $audience, 'label' => $label, 'href' => $href, 'sort_order' => $order, 'is_active' => true, 'created_at' => $published['created_at'], 'updated_at' => $published['updated_at']]);
        }
    }
}
