<?php

namespace Database\Seeders\Homepage;

use Illuminate\Support\Facades\DB;

class FaqSeeder extends HomepageSectionSeeder
{
    public function run(): void
    {
        $published = $this->published();
        $section = $this->heading('homepage_faq_sections', 'Frequently asked questions:', 'Everything You Need to Know.', 'Find helpful information about reservations, experiences, and your stay with Ini Vie Hospitality.');
        $items = [
            ['What is Ini Vie Hospitality?', "Ini Vie Hospitality is a leading Bali-based hospitality management company specializing in luxury villas, resorts, and restaurants. We are renowned for our 'Instagrammable' designs, intimate service, and modern lifestyle concepts."],
            ['Where are Ini Vie Hospitality properties located?', 'Our properties are situated in Bali’s most sought-after locations, including Ubud, Canggu, Seminyak, Legian, Sanur, and Jimbaran. Each property is uniquely designed to reflect the local character of its surroundings.'],
            ['How can I get the best rates for a reservation?', 'To secure the best rates and access exclusive offers, guests are encouraged to book directly through our official property websites or contact our central reservations team via WhatsApp.'],
            ['Do you offer special packages for honeymoons or anniversaries?', 'Yes, we are specialists in creating romantic moments. We offer various add-on packages such as romantic flower decorations for the bed or pool, floating breakfasts, and private candlelight dinners.'],
            ['Does Ini Vie Hospitality provide airport transfer services?', 'We offer airport transportation services for an additional fee. Guests may request this service during the booking process or by contacting the villa staff at least 24 hours prior to arrival.'],
            ['Do all villas feature a private pool?', 'The majority of villa units managed by Ini Vie Hospitality are equipped with private pools to ensure maximum privacy and comfort for every guest.'],
            ['What in-room entertainment facilities are available?', 'To support modern comfort, most of our properties are equipped with smart technology, including smart speakers (Alexa), Netflix access, and high-speed Wi-Fi.'],
            ['Can non-staying guests dine at Ini Vie restaurants?', 'Certainly. We manage several popular restaurants across Bali that are open to the public. Guests can enjoy a variety of culinary experiences, ranging from authentic local dishes to international cuisine.'],
            ['Are Spa services available in-villa?', 'Yes, we provide professional massage and spa treatments that can be performed directly within the villa, allowing guests to relax without leaving the comfort of their room.'],
        ];

        foreach ($items as $index => [$question, $answer]) {
            DB::table('homepage_faq_items')->insert(['section_id' => $section, 'question' => $question, 'answer' => $answer, 'sort_order' => $index, ...$published]);
        }
    }
}
