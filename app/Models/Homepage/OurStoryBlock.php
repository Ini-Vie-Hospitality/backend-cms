<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OurStoryBlock extends HomepageItemModel
{
    protected $table = 'homepage_story_blocks';

    /** @return BelongsTo<OurStory, $this> */
    public function ourStory(): BelongsTo
    {
        return $this->belongsTo(OurStory::class, 'homepage_our_story_id');
    }
}
