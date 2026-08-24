<?php

namespace App\Services\Homepage;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageWorkspaceContext
{
    public function __construct(private Request $request) {}

    public function use(string $key): void
    {
        abort_unless(in_array($key, ['draft', 'published'], true), 404);
        $this->request->attributes->set('homepage_workspace', $key);
    }

    public function key(): string
    {
        return $this->request->attributes->get('homepage_workspace')
            ?? DB::table('homepage_workspace_state')->value('editing_mode')
            ?? 'published';
    }

    public function id(?string $key = null): int
    {
        return (int) DB::table('homepage_workspaces')->where('key', $key ?? $this->key())->value('id');
    }

    public function root(string $table, ?string $key = null): Builder
    {
        return DB::table($table)->where('workspace_id', $this->id($key));
    }

    public function visible(Builder $query): Builder
    {
        if ($this->key() === 'draft') {
            return $query->whereIn('status', ['draft', 'published']);
        }

        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
