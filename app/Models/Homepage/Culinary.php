<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Culinary extends HomepageModel
{
    protected $table = 'homepage_culinary_sections';

    /** @return HasMany<CulinaryItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(CulinaryItem::class, 'section_id');
    }
}
