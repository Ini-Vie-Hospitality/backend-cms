<?php

namespace App\Services\Homepage\Content;

class ContentMedia
{
    public function url(?string $path): string
    {
        if (! $path || str_starts_with($path, 'http') || str_starts_with($path, '/')) {
            return $path ?? '';
        }

        return url('/storage/'.$path);
    }
}
