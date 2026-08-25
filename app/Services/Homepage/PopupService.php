<?php

namespace App\Services\Homepage;

use App\Http\Requests\Homepage\UpdatePopupRequest;
use App\Services\HomepageMediaService;

class PopupService
{
    public function __construct(private HomepageMediaService $media, private HomepageWorkspaceContext $workspace) {}

    public function update(UpdatePopupRequest $request): void
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->media->store($request->file('image'));
        }

        $values = [
            'redirect_url' => $data['redirect_url'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ];

        if (isset($data['image_path'])) {
            $values['image_path'] = $data['image_path'];
        }

        $this->workspace->root('homepage_popups')->update($values);
    }
}
