<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Wellness extends HomepageModel
{
    protected $table = 'homepage_wellness_sections';

    /** @return HasMany<WellnessItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(WellnessItem::class, 'homepage_wellness_id');
    }
}
