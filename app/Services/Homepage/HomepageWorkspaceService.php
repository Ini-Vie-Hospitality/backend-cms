<?php

namespace App\Services\Homepage;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HomepageWorkspaceService
{
    public function __construct(private HomepageWorkspaceTransferService $transfer) {}

    public function mode(): string
    {
        return DB::table('homepage_workspace_state')->value('editing_mode') ?? 'published';
    }

    /** @return Collection<int, \stdClass> */
    public function versions(): Collection
    {
        return DB::table('homepage_publish_versions')->orderByDesc('version')->get();
    }

    public function switch(string $mode, ?int $userId): void
    {
        abort_unless(in_array($mode, ['draft', 'published'], true), 422);
        if ($mode === 'draft') {
            $this->ensureDraft();
        }
        DB::table('homepage_workspace_state')->update([
            'editing_mode' => $mode,
            'updated_by' => $userId,
            'updated_at' => now(),
        ]);
    }

    public function ensureDraft(): void
    {
        $draftId = DB::table('homepage_workspaces')->where('key', 'draft')->value('id');
        if (DB::table('homepage_navbars')->where('workspace_id', $draftId)->exists()) {
            return;
        }
        DB::transaction(fn () => $this->transfer->replace('published', 'draft'));
    }

    public function importDraft(?int $userId): int
    {
        $this->ensureDraft();

        return DB::transaction(function () use ($userId): int {
            $snapshot = $this->transfer->snapshot('published');
            $version = ((int) DB::table('homepage_publish_versions')->max('version')) + 1;
            DB::table('homepage_publish_versions')->insert([
                'version' => $version,
                'payload' => json_encode($snapshot, JSON_THROW_ON_ERROR),
                'media_paths' => json_encode($this->transfer->mediaPaths($snapshot), JSON_THROW_ON_ERROR),
                'action' => 'import',
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->transfer->replace('draft', 'published', true);

            return $version;
        });
    }

    public function rollback(int $versionId, ?int $userId): void
    {
        DB::transaction(function () use ($versionId, $userId): void {
            $version = DB::table('homepage_publish_versions')->where('id', $versionId)->firstOrFail();
            $current = $this->transfer->snapshot('published');
            $next = ((int) DB::table('homepage_publish_versions')->max('version')) + 1;
            DB::table('homepage_publish_versions')->insert([
                'version' => $next,
                'payload' => json_encode($current, JSON_THROW_ON_ERROR),
                'media_paths' => json_encode($this->transfer->mediaPaths($current), JSON_THROW_ON_ERROR),
                'action' => 'rollback-safety',
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->transfer->restore(json_decode($version->payload, true, flags: JSON_THROW_ON_ERROR), 'published');
        });
    }
}
