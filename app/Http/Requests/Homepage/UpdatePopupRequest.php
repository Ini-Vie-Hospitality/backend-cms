<?php

namespace App\Http\Requests\Homepage;

class UpdatePopupRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => $this->publicationRules(),
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'redirect_url' => ['required', 'url', 'max:2048'],
        ];
    }
}
