<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FaqSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_faq_sections', 'Frequently Asked Questions', 'Everything You Need to Know.', 'Find helpful information about reservations, check-in, experiences, and your stay.');
        foreach ([['What Is Ini Vie Hospitality?', 'Ini Vie Hospitality is a collection of thoughtfully designed stays and experiences in Bali.'], ['How Can I Get The Best Rate When Booking?', 'Book directly through official channels for current offers.']] as $index => [$question, $answer]) {
            DB::table('homepage_faq_items')->insert(['section_id' => $section, 'question' => $question, 'answer' => $answer, 'sort_order' => $index, ...$published]);
        }
    }
}
