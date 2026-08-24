<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class FeaturedProperty extends HomepageModel
{
    protected $table = 'homepage_featured_property_sections';

    /** @return HasMany<FeaturedPropertyItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(FeaturedPropertyItem::class, 'homepage_featured_property_id');
    }
}
