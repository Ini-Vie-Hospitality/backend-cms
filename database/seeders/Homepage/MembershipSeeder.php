<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class MembershipSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $membership = DB::table('homepage_memberships')->insertGetId(['title' => 'JOIN WEINIVIE MEMBERSHIP', 'subtitle' => 'Turn Bali Into Yours. Make Every Journey More Rewarding.', 'description' => 'Become a WEINIVIE member and enjoy exclusive access to unforgettable experiences across Bali. Discover special privileges, personalized offers, and curated moments designed just for you.', 'video_path' => '/cta.mp4', 'primary_label' => 'Become a Member', 'primary_href' => 'https://booking.inivie.com/register', 'secondary_label' => 'Discover More', 'secondary_href' => 'https://inivie.com/membership', ...$published]);
        foreach ([['Priority VIP Welcome', 'diamond'], ['Special Celebration Setup', 'gift'], ['Exclusive Savings at Restaurants, Spa & Club Outlets', 'shopping-bag'], ['Access to Monthly Member Promotions', 'tags']] as $index => [$label, $icon]) {
            DB::table('homepage_membership_benefits')->insert(['membership_id' => $membership, 'label' => $label, 'icon' => $icon, 'sort_order' => $index, 'is_active' => true, 'created_at' => $published['created_at'], 'updated_at' => $published['updated_at']]);
        }
    }
}
