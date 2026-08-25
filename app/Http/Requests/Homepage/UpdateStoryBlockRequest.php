<?php

namespace App\Http\Requests\Homepage;

class UpdateStoryBlockRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['title' => ['required', 'string', 'max:255'], 'description' => ['required', 'string', 'max:10000'], 'image_alt' => ['required', 'string', 'max:255'], 'cta_label' => ['required', 'string', 'max:255'], 'href' => ['required', 'string', 'max:2048'], 'status' => $this->publicationRules(), 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:51200']];
    }
}
