<?php

namespace App\Models\Concierge;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string|null $category
 * @property string $content
 * @property string|null $source_url
 * @property string|null $embedding
 * @property string|null $content_hash
 * @property string $status
 * @property CarbonInterface|null $published_at
 */
class KnowledgeItem extends Model
{
    protected $table = 'concierge_knowledge_items';

    protected $guarded = ['id'];

    protected $hidden = ['embedding'];

    protected static function booted(): void
    {
        static::saving(function (self $item): void {
            $hash = hash('sha256', trim($item->title).'|'.trim($item->content));
            if ($item->exists && $item->content_hash !== $hash) {
                $item->embedding = null;
            }
            $item->content_hash = $hash;
            $item->published_at = $item->status === 'published' ? ($item->published_at ?? now()) : null;
        });
    }

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    /** @param Builder<self> $query */
    public function scopeSearchable(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at')->whereNotNull('embedding');
    }
}
