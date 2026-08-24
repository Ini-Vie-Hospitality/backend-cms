<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\ImportHomepageDraftRequest;
use App\Http\Requests\Homepage\SwitchHomepageWorkspaceRequest;
use App\Services\Homepage\HomepageRevalidationService;
use App\Services\Homepage\HomepageWorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceController extends Controller
{
    public function update(SwitchHomepageWorkspaceRequest $request, HomepageWorkspaceService $workspaces): RedirectResponse
    {
        $data = $request->validated();
        $workspaces->switch($data['mode'], $request->user()?->id);
        Inertia::flash('toast', ['type' => 'success', 'message' => ucfirst($data['mode']).' mode enabled.']);

        return back();
    }

    public function preview(Request $request): Response
    {
        $expires = now()->addMinutes(15)->timestamp;
        $signature = hash_hmac('sha256', (string) $expires, (string) config('services.homepage.preview_secret'));

        return Inertia::render('homepage/preview', [
            'publishedUrl' => rtrim((string) config('services.homepage.frontend_url'), '/').'/',
            'draftUrl' => rtrim((string) config('services.homepage.frontend_url'), '/')."/preview?expires={$expires}&signature={$signature}",
        ]);
    }

    public function import(ImportHomepageDraftRequest $request, HomepageWorkspaceService $workspaces, HomepageRevalidationService $revalidation): RedirectResponse
    {
        $version = $workspaces->importDraft($request->user()?->id);
        $revalidation->revalidate();
        Inertia::flash('toast', ['type' => 'success', 'message' => "Draft imported to published. Backup version {$version} created."]);

        return back();
    }

    public function history(HomepageWorkspaceService $workspaces): Response
    {
        return Inertia::render('homepage/history', [
            'versions' => $workspaces->versions(),
        ]);
    }

    public function rollback(Request $request, int $version, HomepageWorkspaceService $workspaces, HomepageRevalidationService $revalidation): RedirectResponse
    {
        $workspaces->rollback($version, $request->user()?->id);
        $revalidation->revalidate();
        Inertia::flash('toast', ['type' => 'success', 'message' => 'Published content restored.']);

        return back();
    }
}
