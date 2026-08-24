<?php

namespace App\Models\Homepage;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class HomepageItemModel extends Model
{
    protected $guarded = ['id'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    /** @param Builder<static> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at');
    }

    /** @param Builder<static> $query */
    public function scopeSorted(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
