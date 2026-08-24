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
use Database\Seeders\Homepage\SpecialOffersSeeder;
use Database\Seeders\Homepage\WellnessSeeder;
use Database\Seeders\Homepage\WhatsNewSeeder;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([NavbarSeeder::class, BrandIntroductionSeeder::class, FeaturedPropertiesSeeder::class, CulinarySeeder::class, WellnessSeeder::class, MembershipSeeder::class, OurStorySeeder::class, SpecialOffersSeeder::class, WhatsNewSeeder::class, FeaturedInSeeder::class, FaqSeeder::class, FooterSeeder::class]);
    }
}
