<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FooterSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $footer = DB::table('homepage_footers')->insertGetId([
            'aria_label' => 'Ini Vie Hospitality footer',
            'background_image_path' => '/bg-footer.png',
            'logo_path' => '/inivie-white.png',
            'logo_alt' => 'Ini Vie Hospitality',
            'summary' => 'Curating meaningful stays, destinations, wellness, and lifestyle experiences across Bali.',
            'office_title' => 'Head office',
            'office_address' => 'Jl. Persada II No.888, Kerobokan, Kec. Kuta Utara, Kabupaten Badung, Bali 80361',
            'office_phone_label' => '+62 361 9346082',
            'office_phone_href' => 'tel:+623619346082',
            'office_email_label' => 'info@inivie.com',
            'office_email_href' => 'mailto:info@inivie.com',
            'office_map_label' => 'View on map',
            'office_map_href' => 'https://maps.app.goo.gl/uzTWDwHNTNhyLxH28',
            'subscribe_title' => 'Subscribe',
            'subscribe_description' => 'Receive latest offers and promos without spam',
            'subscribe_action_label' => 'Subscribe',
            'subscribe_action_href' => 'https://inivie.com/subscribe',
            'socials_title' => 'Follow Our Social Media',
            'policy_label' => 'General Policy',
            'policy_href' => 'https://inivie.com/policy',
            'copyright' => '2026 iNi ViE Hospitality. All Rights Reserved',
            ...$published,
        ]);
        $contacts = [
            ['Marketing', '+62 812-3868-7387', 'https://wa.me/6281238687387', 'marcom@inivie.com', [['Collaborate with us', 'https://inivie.com/creators'], ['Submit your proposal', 'https://inivie.com/marketinginquiry']]],
            ['Media Inquiry', '+62 813 3753-0285', 'https://wa.me/6281337530285', 'pr@inivie.com', []],
            ['Human Resource', '+62 812-3729-0110', 'https://wa.me/6281237290110', 'hire@inivie.com', [['View open jobs', 'https://inivie.com/hiring']]],
            ['Reservation', '+62 811-3986-889', 'https://wa.me/628113986889', 'reservation@inivie.com', [['Submit your inquiry', 'https://inivie.com/salesinquiry']]],
            ['Travel Agent Inquiry', '+62 811-3986-889', 'https://wa.me/628113986889', 'salescoordinator@inivie.com', [['Submit your inquiry', 'https://inivie.com/salesinquiry']]],
            ['Owners', null, null, null, [['About Us', 'https://inivie.com/about']]],
        ];

        foreach ($contacts as $index => [$title, $phone, $phoneHref, $email, $actions]) {
            $contact = DB::table('homepage_footer_contacts')->insertGetId([
                'footer_id' => $footer,
                'title' => $title,
                'phone_label' => $phone,
                'phone_href' => $phoneHref,
                'email_label' => $email,
                'email_href' => $email ? 'mailto:'.$email : null,
                'sort_order' => $index,
                'is_active' => true,
                'created_at' => $published['created_at'],
                'updated_at' => $published['updated_at'],
            ]);

            foreach ($actions as $actionIndex => [$label, $href]) {
                DB::table('homepage_footer_contact_actions')->insert([
                    'contact_id' => $contact,
                    'label' => $label,
                    'href' => $href,
                    'sort_order' => $actionIndex,
                    'is_active' => true,
                    'created_at' => $published['created_at'],
                    'updated_at' => $published['updated_at'],
                ]);
            }
        }
        foreach ([
            ['Facebook', 'https://www.facebook.com/iniviebali/', 'facebook'],
            ['Instagram', 'https://www.instagram.com/iniviehospitality/', 'instagram'],
            ['LinkedIn', 'https://www.linkedin.com/company/iniviehospitality/', 'linkedin'],
            ['YouTube', 'https://www.youtube.com/@INIVIEHOSPITALITY', 'youtube'],
            ['Tiktok', 'https://www.tiktok.com/@iniviehospitality_', 'tiktok'],
        ] as $index => [$label, $href, $icon]) {
            DB::table('homepage_footer_socials')->insert(['footer_id' => $footer, 'label' => $label, 'href' => $href, 'icon' => $icon, 'sort_order' => $index, 'is_active' => true, 'created_at' => $published['created_at'], 'updated_at' => $published['updated_at']]);
        }
    }
}
