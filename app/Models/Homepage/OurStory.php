<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Relations\HasMany;

class OurStory extends HomepageModel
{
    protected $table = 'homepage_story_sections';

    /** @return HasMany<OurStoryBlock, $this> */
    public function blocks(): HasMany
    {
        return $this->hasMany(OurStoryBlock::class, 'homepage_our_story_id');
    }
}
