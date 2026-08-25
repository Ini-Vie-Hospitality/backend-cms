<?php

namespace App\Services\Homepage\Content;

use App\Services\Homepage\HomepageWorkspaceContext;

class PopupContentService
{
    public function __construct(private ContentMedia $media, private HomepageWorkspaceContext $workspace) {}

    /** @return array<string, string>|null */
    public function popup(): ?array
    {
        $row = $this->workspace->visible($this->workspace->root('homepage_popups'))->first();

        if ($row === null || $row->image_path === null || $row->redirect_url === '') {
            return null;
        }

        return [
            'image' => $this->media->url($row->image_path),
            'alt' => $row->image_alt,
            'href' => $row->redirect_url,
        ];
    }
}
