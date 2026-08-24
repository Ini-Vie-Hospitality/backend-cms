<?php

namespace App\Services;

use App\Services\Homepage\Content\EditorialContentService;
use App\Services\Homepage\Content\FooterContentService;
use App\Services\Homepage\Content\NavigationContentService;
use App\Services\Homepage\Content\ShowcaseContentService;

class HomepageContentService
{
    public function __construct(
        private NavigationContentService $navigation,
        private ShowcaseContentService $showcases,
        private EditorialContentService $editorial,
        private FooterContentService $footer,
    ) {}

    /** @return array<string, array<string, mixed>|null> */
    public function published(): array
    {
        return [
            'navbar' => $this->navigation->navbar(),
            'brandIntroduction' => $this->navigation->brandIntroduction(),
            'featuredProperties' => $this->showcases->featuredProperties(),
            'culinary' => $this->showcases->culinary(),
            'wellness' => $this->showcases->wellness(),
            'membership' => $this->editorial->membership(),
            'ourStory' => $this->editorial->ourStory(),
            'specialOffers' => $this->editorial->specialOffers(),
            'whatsNew' => $this->editorial->whatsNew(),
            'featuredIn' => $this->editorial->featuredIn(),
            'faq' => $this->showcases->faq(),
            'footer' => $this->footer->footer(),
        ];
    }
}
