<?php

namespace App\Http\Requests\Homepage;

class SaveFooterContactRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['title' => ['required', 'string', 'max:255'], 'phone_label' => ['nullable', 'string', 'max:255'], 'phone_href' => ['nullable', 'string', 'max:2048'], 'email_label' => ['nullable', 'email', 'max:255'], 'email_href' => ['nullable', 'string', 'max:2048'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['required', 'boolean']];
    }
}
