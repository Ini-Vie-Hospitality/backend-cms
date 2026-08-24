<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HomepageMediaService
{
    public function __construct(private HomepageMediaReferenceService $references) {}

    public function store(UploadedFile $file): string
    {
        $path = $file->store('homepage', 'public');
        if ($path === false) {
            throw new \RuntimeException('Unable to store homepage media.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if (! $this->isManaged($path) || $this->references->exists($path)) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    private function isManaged(?string $path): bool
    {
        return is_string($path) && preg_match('#^homepage/[A-Za-z0-9][A-Za-z0-9._/-]*$#', $path) === 1 && ! str_contains($path, '..') && ! str_contains($path, '//');
    }
}
