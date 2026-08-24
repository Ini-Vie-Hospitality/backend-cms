<?php

namespace App\Http\Requests\Homepage;

class SaveFooterSocialRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['label' => ['required', 'string', 'max:255'], 'href' => ['required', 'string', 'max:2048'], 'icon' => ['required', 'in:facebook,instagram,linkedin,youtube,tiktok'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['required', 'boolean']];
    }
}
