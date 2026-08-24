<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedInItem extends HomepageItemModel
{
    protected $table = 'homepage_featured_in_logos';

    /** @return BelongsTo<FeaturedIn, $this> */
    public function featuredIn(): BelongsTo
    {
        return $this->belongsTo(FeaturedIn::class, 'section_id');
    }
}
