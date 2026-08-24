<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $published = ['status' => 'published', 'published_at' => $now, 'created_at' => $now, 'updated_at' => $now];
        $navbar = DB::table('homepage_navbars')->insertGetId(['logo_path' => '/inivie-white.png', 'logo_alt' => 'Ini Vie Hospitality', 'logo_href' => '/', 'book_label' => 'Book Your Stay', 'book_href' => '#booking', 'mobile_eyebrow' => 'Explore Ini Vie', 'mobile_open_label' => 'Open menu', 'mobile_close_label' => 'Close menu', ...$published]);
        foreach ([['desktop', 'Stays', '#stays'], ['desktop', 'Dining', '#dining'], ['desktop', 'Wellness', '#wellness'], ['desktop', 'Membership', '#membership'], ['desktop', 'Offers', '#offers'], ['mobile', 'About', '#about'], ['mobile', 'Stays', '#stays'], ['mobile', 'Dining', '#dining'], ['mobile', 'Wellness', '#wellness'], ['mobile', 'Membership', '#membership'], ['mobile', 'Our Story', '#our-story'], ['mobile', 'Special Offers', '#offers'], ['mobile', "What's New", '#journal'], ['mobile', 'Featured In', '#featured-in'], ['mobile', 'FAQ', '#faq']] as $order => [$audience,$label,$href]) {
            DB::table('homepage_navbar_links')->insert(['navbar_id' => $navbar, 'audience' => $audience, 'label' => $label, 'href' => $href, 'sort_order' => $order, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }
        $brand = DB::table('homepage_brand_introductions')->insertGetId(['title' => 'More than places to stay. We create reasons to remember Bali.', 'quote' => 'Rooted in Bali — made for meaningful journeys.', ...$published]);
        foreach (['BALI', 'INI VIE'] as $i => $text) {
            DB::table('homepage_brand_introduction_words')->insert(['brand_introduction_id' => $brand, 'slot' => $i + 1, 'text' => $text]);
        }
        foreach (['iNi ViE Hospitality is a collection of thoughtfully designed stays and experiences inspired by the warmth, culture, and beauty of Bali.', 'From private villas and distinctive resorts to culinary journeys and restorative wellness, every experience is created around one belief — hospitality should feel personal.'] as $i => $body) {
            DB::table('homepage_brand_introduction_paragraphs')->insert(['brand_introduction_id' => $brand, 'slot' => $i + 1, 'body' => $body]);
        }
        foreach ([['/brand-introduction/villa-desktop.webp', 'A tropical Bali villa surrounded by lush gardens'], ['/brand-introduction/breakfast-desktop.webp', 'A guest enjoying breakfast beside a tropical pool'], ['/brand-introduction/offering-desktop.webp', 'A traditional Balinese floral offering']] as $i => [$path,$alt]) {
            DB::table('homepage_brand_introduction_images')->insert(['brand_introduction_id' => $brand, 'slot' => $i + 1, 'image_path' => $path, 'image_alt' => $alt]);
        }

        $propertySection = $this->heading('homepage_featured_property_sections', 'Curated Collection', 'Featured Property For You', 'Exclusive stays designed to make your getaway unforgettable.', ['default_cta_label' => 'Explore Property', 'scroll_label' => 'Scroll to explore'], $published);
        foreach ([['Leedon Villa Seminyak', 'Luxury Villa', 'Elegant villa living with warm tropical design.', '/properties/leedon-villa.webp', '#leedon-villa'], ['Ajowa Resort', 'Resort Experience', 'A refined resort experience blending tropical atmosphere and comfort.', '/properties/ajowa.avif', '#ajowa-resort'], ['La Mewali Resort', 'Resort Experience', 'A considered retreat shaped by lush surroundings.', '/properties/la-mewali.webp', '#la-mewali-resort']] as $i => [$name,$category,$description,$image,$href]) {
            DB::table('homepage_featured_properties')->insert(['section_id' => $propertySection, 'name' => $name, 'category' => $category, 'description' => $description, 'image_path' => $image, 'image_alt' => $name, 'href' => $href, 'cta_label' => 'Explore Property', 'sort_order' => $i, ...$published]);
        }
        $culinary = $this->heading('homepage_culinary_sections', 'The Culinary Journey', 'A Journey Through Taste.', 'Opening a new chapter in refined dining experience.', ['scroll_label' => 'Scroll to explore'], $published);
        foreach ([['Norii Seminyak', 'Seminyak, Bali', 'Japanese Dining'], ['Riserva Steakhouse', 'Ubud, Bali', 'Open Fire'], ['Terra Verte', 'Ubud, Bali', 'Mediterranean'], ['Habitat Bistro', 'Ubud, Bali', 'Contemporary Bistro']] as $i => [$name,$location,$eyebrow]) {
            DB::table('homepage_culinary_destinations')->insert(['section_id' => $culinary, 'name' => $name, 'location' => $location, 'eyebrow' => $eyebrow, 'description' => 'A refined dining experience shaped by Bali.', 'schedule' => 'Dinner · 17:00 — 23:00', 'cta_label' => 'Discover '.$name, 'image_path' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=900&q=85', 'image_alt' => $name.' dining experience', 'href' => '#'.str($name)->slug(), 'sort_order' => $i, ...$published]);
        }
        $wellness = $this->heading('homepage_wellness_sections', 'iNi ViE Wellness', 'Wellness Harmony Escape', 'Find serenity in soulful rituals made to restore.', [], $published);
        foreach ([['Svaha Spa Ajowa', 'Seminyak, Bali'], ['Svaha Spa La Mewali', 'Canggu, Bali'], ['Svaha Spa Bisma', 'Ubud, Bali'], ['Svaha Wellness', 'Nusa Dua, Bali']] as $i => [$name,$location]) {
            DB::table('homepage_wellness_escapes')->insert(['section_id' => $wellness, 'name' => $name, 'location' => $location, 'description' => 'A restorative sanctuary shaped around touch, stillness, and natural beauty.', 'image_path' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1600&q=88', 'image_alt' => $name.' wellness experience', 'href' => '#'.str($name)->slug(), 'cta_label' => 'Discover Experience', 'sort_order' => $i, ...$published]);
        }

        $membership = DB::table('homepage_memberships')->insertGetId(['title' => 'Join Weinivie Membership', 'subtitle' => 'Turn Bali Into Yours. Make Every Journey More Rewarding.', 'description' => 'Become a WEINIVIE member and enjoy exclusive access to unforgettable experiences across Bali.', 'video_path' => '/cta.mp4', 'primary_label' => 'Become a Member', 'primary_href' => 'https://booking.inivie.com/en/register', 'secondary_label' => 'Discover More', 'secondary_href' => '/membership', ...$published]);
        foreach ([['Priority VIP Welcome', 'diamond'], ['Special Celebration Setup', 'gift'], ['Exclusive Savings at Restaurants, Spa & Club Outlets', 'shopping-bag'], ['Access to Monthly Member Promotions', 'tags']] as $i => [$label,$icon]) {
            DB::table('homepage_membership_benefits')->insert(['membership_id' => $membership, 'label' => $label, 'icon' => $icon, 'sort_order' => $i, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }
        $story = DB::table('homepage_story_sections')->insertGetId(['title' => 'Our Story', 'description' => 'iNi ViE Hospitality guided by eight mantras that honour people, culture, and nature.', ...$published]);
        foreach ([['About Us', '/our-story/infinity-pool.png'], ['What Makes Us Different', '/our-story/meaningful-journey.png'], ['Our Eight Mantras', '/our-story/eight-mantras.jpg'], ['Sustainability', '/our-story/sustainability.jpg']] as $i => [$title,$image]) {
            DB::table('homepage_story_blocks')->insert(['section_id' => $story, 'slot' => $i + 1, 'title' => $title, 'description' => 'Discover the values and meaningful experiences that shape iNi ViE Hospitality.', 'image_path' => $image, 'image_alt' => $title, 'cta_label' => $i ? 'Discover More' : 'Explore Our Story', 'href' => 'https://inivie.com/about', ...$published]);
        }
        $offers = $this->heading('homepage_special_offer_sections', 'Our Special Offers', 'Your Bali escape, thoughtfully elevated.', 'Thoughtfully curated stays, seasonal privileges, and exclusive benefits.', ['all_offers_label' => 'Explore All Offers', 'all_offers_href' => 'https://inivie.com/offers'], $published);
        foreach ([['01', 'Plan Ahead', 'Early Getaway', '/offers/early-getaway.png'], ['02', 'Curated Privileges', 'Bali Yours', '/offers/bali-yours.png'], ['03', 'Advance Reservations', 'Advance Saver', '/offers/advance-saver.png']] as $i => [$number,$category,$title,$image]) {
            DB::table('homepage_special_offers')->insert(['section_id' => $offers, 'slot' => $i + 1, 'display_number' => $number, 'category' => $category, 'title' => $title, 'description' => 'Exclusive privileges created for a memorable Bali escape.', 'image_path' => $image, 'image_alt' => $title, 'href' => 'https://inivie.com/offers/'.str($title)->slug(), ...$published]);
        }
        $journal = $this->heading('homepage_journal_sections', "What's New", 'Stories from Bali & Beyond.', 'Thoughtful guides, rituals, places, and discoveries from across Bali.', ['explore_label' => 'Explore The Story', 'explore_href' => '#journal-nusa-penida', 'read_label' => 'Read Story'], $published);
        foreach ([['nusa-penida', 'Destination', 'Nusa Penida,', 'Beyond the Postcard'], ['quiet-art', 'Wellness · Ubud', 'The Quiet Art', 'of Slowing Down'], ['sacred-places', 'Bali Culture', 'Sacred Places,', 'Timeless Traditions'], ['september-guide', 'Seasonal Guide', 'Bali in September:', 'A Guide to the Season']] as $i => [$key,$category,$line1,$line2]) {
            $id = DB::table('homepage_journal_stories')->insertGetId(['section_id' => $journal, 'external_key' => $key, 'category' => $category, 'description' => 'Discover meaningful stories and places across Bali.', 'reading_time' => '5 min read', 'image_path' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1600&q=85', 'image_alt' => $line1, 'href' => '#journal-'.$key, 'sort_order' => $i, ...$published]);
            foreach ([$line1, $line2] as $line => $text) {
                DB::table('homepage_journal_story_title_lines')->insert(['story_id' => $id, 'line_number' => $line + 1, 'text' => $text]);
            }
        }
        $featuredIn = DB::table('homepage_featured_in_sections')->insertGetId(['title' => 'Featured In', ...$published]);
        foreach ([1, 2, 3, 4, 5, 6, 7, 9, 10] as $i => $number) {
            DB::table('homepage_featured_in_logos')->insert(['section_id' => $featuredIn, 'image_path' => "https://inivie.com/inivie_assets/img/logomedia/$number.png", 'image_alt' => 'Featured publication '.($i + 1), 'sort_order' => $i, ...$published]);
        }
        $faq = $this->heading('homepage_faq_sections', 'Frequently Asked Questions', 'Everything You Need to Know.', 'Find helpful information about reservations, check-in, experiences, and your stay.', [], $published);
        foreach ([['What Is Ini Vie Hospitality?', 'Ini Vie Hospitality is a collection of thoughtfully designed stays and experiences in Bali.'], ['How Can I Get The Best Rate When Booking?', 'Book directly through official channels for current offers.']] as $i => [$question,$answer]) {
            DB::table('homepage_faq_items')->insert(['section_id' => $faq, 'question' => $question, 'answer' => $answer, 'sort_order' => $i, ...$published]);
        }
        $footer = DB::table('homepage_footers')->insertGetId(['aria_label' => 'Ini Vie Hospitality footer', 'background_image_path' => '/bg-footer.png', 'logo_path' => '/inivie-white.png', 'logo_alt' => 'Ini Vie Hospitality', 'summary' => 'Curating meaningful stays, destinations, wellness, and lifestyle experiences across Bali.', 'office_title' => 'Head office', 'office_address' => 'Jl. Persada II No.888, Kerobokan, Bali 80361', 'office_phone_label' => '+62 361 9346082', 'office_phone_href' => 'tel:+623619346082', 'office_email_label' => 'info@inivie.com', 'office_email_href' => 'mailto:info@inivie.com', 'office_map_label' => 'View on map', 'office_map_href' => '#map', 'subscribe_title' => 'Subscribe', 'subscribe_description' => 'Receive latest offers and promos without spam', 'subscribe_action_label' => 'Subscribe', 'subscribe_action_href' => '#subscribe', 'socials_title' => 'Follow Our Social Media', 'policy_label' => 'General Policy', 'policy_href' => '#policy', 'copyright' => '2026 iNi ViE Hospitality. All Rights Reserved', ...$published]);
        foreach ([['Marketing', '+62 812-3868-7387', 'marcom@inivie.com'], ['Reservation', '+62 811-3986-889', 'reservation@inivie.com']] as $i => [$title,$phone,$email]) {
            DB::table('homepage_footer_contacts')->insert(['footer_id' => $footer, 'title' => $title, 'phone_label' => $phone, 'phone_href' => 'tel:'.str_replace([' ', '-'], '', $phone), 'email_label' => $email, 'email_href' => 'mailto:'.$email, 'sort_order' => $i, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }
        foreach ([['Facebook', 'facebook'], ['Instagram', 'instagram'], ['LinkedIn', 'linkedin'], ['YouTube', 'youtube'], ['Tiktok', 'tiktok']] as $i => [$label,$icon]) {
            DB::table('homepage_footer_socials')->insert(['footer_id' => $footer, 'label' => $label, 'href' => '#social', 'icon' => $icon, 'sort_order' => $i, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    /**
     * @param  array<string, mixed>  $extra
     * @param  array<string, mixed>  $published
     */
    private function heading(string $table, string $eyebrow, string $title, string $description, array $extra, array $published): int
    {
        return DB::table($table)->insertGetId(['eyebrow' => $eyebrow, 'title' => $title, 'description' => $description, ...$extra, ...$published]);
    }
}
