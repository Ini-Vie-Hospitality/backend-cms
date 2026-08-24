<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WellnessItem extends HomepageItemModel
{
    protected $table = 'homepage_wellness_escapes';

    /** @return BelongsTo<Wellness, $this> */
    public function wellness(): BelongsTo
    {
        return $this->belongsTo(Wellness::class, 'homepage_wellness_id');
    }
}
