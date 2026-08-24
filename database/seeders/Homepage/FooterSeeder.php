<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FooterSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $footer = DB::table('homepage_footers')->insertGetId(['aria_label' => 'Ini Vie Hospitality footer', 'background_image_path' => '/bg-footer.png', 'logo_path' => '/inivie-white.png', 'logo_alt' => 'Ini Vie Hospitality', 'summary' => 'Curating meaningful stays, destinations, wellness, and lifestyle experiences across Bali.', 'office_title' => 'Head office', 'office_address' => 'Jl. Persada II No.888, Kerobokan, Bali 80361', 'office_phone_label' => '+62 361 9346082', 'office_phone_href' => 'tel:+623619346082', 'office_email_label' => 'info@inivie.com', 'office_email_href' => 'mailto:info@inivie.com', 'office_map_label' => 'View on map', 'office_map_href' => '#map', 'subscribe_title' => 'Subscribe', 'subscribe_description' => 'Receive latest offers and promos without spam', 'subscribe_action_label' => 'Subscribe', 'subscribe_action_href' => '#subscribe', 'socials_title' => 'Follow Our Social Media', 'policy_label' => 'General Policy', 'policy_href' => '#policy', 'copyright' => '2026 iNi ViE Hospitality. All Rights Reserved', ...$published]);
        foreach ([['Marketing', '+62 812-3868-7387', 'marcom@inivie.com'], ['Reservation', '+62 811-3986-889', 'reservation@inivie.com']] as $index => [$title, $phone, $email]) {
            DB::table('homepage_footer_contacts')->insert(['footer_id' => $footer, 'title' => $title, 'phone_label' => $phone, 'phone_href' => 'tel:'.str_replace([' ', '-'], '', $phone), 'email_label' => $email, 'email_href' => 'mailto:'.$email, 'sort_order' => $index, 'is_active' => true, 'created_at' => $published['created_at'], 'updated_at' => $published['updated_at']]);
        }
        foreach ([['Facebook', 'facebook'], ['Instagram', 'instagram'], ['LinkedIn', 'linkedin'], ['YouTube', 'youtube'], ['Tiktok', 'tiktok']] as $index => [$label, $icon]) {
            DB::table('homepage_footer_socials')->insert(['footer_id' => $footer, 'label' => $label, 'href' => '#social', 'icon' => $icon, 'sort_order' => $index, 'is_active' => true, 'created_at' => $published['created_at'], 'updated_at' => $published['updated_at']]);
        }
    }
}
