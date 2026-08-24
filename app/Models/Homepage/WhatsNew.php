<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsNew extends HomepageModel
{
    protected $table = 'homepage_journal_sections';

    /** @return HasMany<WhatsNewItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(WhatsNewItem::class, 'section_id');
    }
}
