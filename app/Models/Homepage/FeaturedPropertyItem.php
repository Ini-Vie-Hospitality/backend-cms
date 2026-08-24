<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedPropertyItem extends HomepageItemModel
{
    protected $table = 'homepage_featured_properties';

    /** @return BelongsTo<FeaturedProperty, $this> */
    public function featuredProperty(): BelongsTo
    {
        return $this->belongsTo(FeaturedProperty::class, 'homepage_featured_property_id');
    }
}
