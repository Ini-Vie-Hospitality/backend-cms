<?php

use App\Services\Homepage\HomepageWorkspaceContext;
use App\Services\Homepage\HomepageWorkspaceService;
use Database\Seeders\HomepageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HomepageSeeder::class);
});

test('draft edits are isolated until imported and can be rolled back', function () {
    $workspaces = app(HomepageWorkspaceService::class);
    $workspaces->ensureDraft();

    $publishedId = DB::table('homepage_workspaces')->where('key', 'published')->value('id');
    $draftId = DB::table('homepage_workspaces')->where('key', 'draft')->value('id');
    $publishedLabel = DB::table('homepage_navbars')->where('workspace_id', $publishedId)->value('book_label');

    DB::table('homepage_navbars')->where('workspace_id', $draftId)->update(['book_label' => 'Draft booking label']);

    expect(DB::table('homepage_navbars')->where('workspace_id', $publishedId)->value('book_label'))->toBe($publishedLabel);

    $version = $workspaces->importDraft(null);

    expect(DB::table('homepage_navbars')->where('workspace_id', $publishedId)->value('book_label'))->toBe('Draft booking label')
        ->and(DB::table('homepage_publish_versions')->where('version', $version)->exists())->toBeTrue();

    $versionId = DB::table('homepage_publish_versions')->where('version', $version)->value('id');
    $workspaces->rollback($versionId, null);

    expect(DB::table('homepage_navbars')->where('workspace_id', $publishedId)->value('book_label'))->toBe($publishedLabel);
});

test('workspace context selects draft data for preview', function () {
    $workspaces = app(HomepageWorkspaceService::class);
    $workspaces->ensureDraft();
    $context = app(HomepageWorkspaceContext::class);
    $context->use('draft');

    expect($context->key())->toBe('draft')
        ->and($context->root('homepage_navbars')->exists())->toBeTrue();
});
