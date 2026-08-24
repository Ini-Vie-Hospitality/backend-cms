<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends HomepageModel
{
    protected $table = 'homepage_faq_sections';

    /** @return HasMany<FaqItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(FaqItem::class, 'homepage_faq_id');
    }
}
