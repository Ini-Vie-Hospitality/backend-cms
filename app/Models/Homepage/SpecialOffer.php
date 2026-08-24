<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecialOffer extends HomepageModel
{
    protected $table = 'homepage_special_offer_sections';

    /** @return HasMany<SpecialOfferItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(SpecialOfferItem::class, 'homepage_special_offer_id');
    }
}
