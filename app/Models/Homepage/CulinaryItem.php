<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CulinaryItem extends HomepageItemModel
{
    protected $table = 'homepage_culinary_destinations';

    /** @return BelongsTo<Culinary, $this> */
    public function culinary(): BelongsTo
    {
        return $this->belongsTo(Culinary::class, 'homepage_culinary_id');
    }
}
