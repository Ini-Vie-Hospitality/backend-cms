<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class FeaturedIn extends HomepageModel
{
    protected $table = 'homepage_featured_in_sections';

    /** @return HasMany<FeaturedInItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(FeaturedInItem::class, 'section_id');
    }
}
