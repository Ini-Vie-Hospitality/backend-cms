<?php

namespace App\Http\Resources\Homepage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomepageResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'popup' => $this->section('popup', PopupResource::class, $request),
            'navbar' => $this->section('navbar', NavbarResource::class, $request),
            'brandIntroduction' => $this->section('brandIntroduction', BrandIntroductionResource::class, $request),
            'featuredProperties' => $this->section('featuredProperties', FeaturedPropertiesResource::class, $request),
            'culinary' => $this->section('culinary', CulinaryResource::class, $request),
            'wellness' => $this->section('wellness', WellnessResource::class, $request),
            'membership' => $this->section('membership', MembershipResource::class, $request),
            'ourStory' => $this->section('ourStory', OurStoryResource::class, $request),
            'specialOffers' => $this->section('specialOffers', SpecialOffersResource::class, $request),
            'whatsNew' => $this->section('whatsNew', WhatsNewResource::class, $request),
            'featuredIn' => $this->section('featuredIn', FeaturedInResource::class, $request),
            'faq' => $this->section('faq', FaqResource::class, $request),
            'footer' => $this->section('footer', FooterResource::class, $request),
        ];
    }

    /**
     * @param  class-string<JsonResource>  $resource
     * @return array<string, mixed>|null
     */
    private function section(string $key, string $resource, Request $request): ?array
    {
        $value = $this->resource[$key] ?? null;

        return $value === null ? null : (new $resource($value))->resolve($request);
    }
}
