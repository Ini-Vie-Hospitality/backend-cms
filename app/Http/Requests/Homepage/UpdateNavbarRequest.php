<?php

namespace App\Http\Requests\Homepage;

class UpdateNavbarRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'logo_alt' => ['required', 'string', 'max:255'], 'logo_href' => ['required', 'string', 'max:2048'],
            'book_label' => ['required', 'string', 'max:255'], 'book_href' => ['required', 'string', 'max:2048'],
            'mobile_eyebrow' => ['required', 'string', 'max:255'], 'mobile_open_label' => ['required', 'string', 'max:255'],
            'mobile_close_label' => ['required', 'string', 'max:255'], 'status' => $this->publicationRules(),
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
        ];
    }
}
