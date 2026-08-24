<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsNewItem extends HomepageItemModel
{
    protected $table = 'homepage_journal_stories';

    /** @return BelongsTo<WhatsNew, $this> */
    public function whatsNew(): BelongsTo
    {
        return $this->belongsTo(WhatsNew::class, 'homepage_whats_new_id');
    }
}
