<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqItem extends HomepageItemModel
{
    protected $table = 'homepage_faq_items';

    /** @return BelongsTo<Faq, $this> */
    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class, 'section_id');
    }
}
