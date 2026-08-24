<?php

namespace App\Http\Requests\Homepage;

class SaveNavbarLinkRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['audience' => ['required', 'in:desktop,mobile'], 'label' => ['required', 'string', 'max:255'], 'href' => ['required', 'string', 'max:2048'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['required', 'boolean']];
    }
}
