<?php

namespace Database\Seeders;

use Database\Seeders\Homepage\BrandIntroductionSeeder;
use Database\Seeders\Homepage\CulinarySeeder;
use Database\Seeders\Homepage\FaqSeeder;
use Database\Seeders\Homepage\FeaturedInSeeder;
use Database\Seeders\Homepage\FeaturedPropertiesSeeder;
use Database\Seeders\Homepage\FooterSeeder;
use Database\Seeders\Homepage\MembershipSeeder;
use Database\Seeders\Homepage\NavbarSeeder;
use Database\Seeders\Homepage\OurStorySeeder;
use Database\Seeders\Homepage\PopupSeeder;
use Database\Seeders\Homepage\SpecialOffersSeeder;
use Database\Seeders\Homepage\WellnessSeeder;
use Database\Seeders\Homepage\WhatsNewSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([NavbarSeeder::class, BrandIntroductionSeeder::class, FeaturedPropertiesSeeder::class, CulinarySeeder::class, WellnessSeeder::class, MembershipSeeder::class, OurStorySeeder::class, SpecialOffersSeeder::class, WhatsNewSeeder::class, FeaturedInSeeder::class, FaqSeeder::class, FooterSeeder::class, PopupSeeder::class]);
        $publishedId = DB::table('homepage_workspaces')->where('key', 'published')->value('id');
        foreach (['homepage_navbars', 'homepage_brand_introductions', 'homepage_featured_property_sections', 'homepage_culinary_sections', 'homepage_wellness_sections', 'homepage_special_offer_sections', 'homepage_journal_sections', 'homepage_faq_sections', 'homepage_featured_in_sections', 'homepage_story_sections', 'homepage_memberships', 'homepage_footers', 'homepage_wellness_categories'] as $table) {
            DB::table($table)->whereNull('workspace_id')->update(['workspace_id' => $publishedId]);
        }
    }
}
