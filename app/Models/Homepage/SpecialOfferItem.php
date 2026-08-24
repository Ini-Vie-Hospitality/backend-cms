<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialOfferItem extends HomepageItemModel
{
    protected $table = 'homepage_special_offers';

    /** @return BelongsTo<SpecialOffer, $this> */
    public function specialOffer(): BelongsTo
    {
        return $this->belongsTo(SpecialOffer::class, 'section_id');
    }
}
