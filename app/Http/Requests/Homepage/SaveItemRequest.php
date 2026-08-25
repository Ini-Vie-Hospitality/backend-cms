<?php

namespace App\Http\Requests\Homepage;

use App\Support\HomepageDefinitions;

class SaveItemRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        $definition = HomepageDefinitions::item((string) $this->route('section'));
        $rules = ['sort_order' => ['required', 'integer', 'min:0'], 'status' => $this->publicationRules()];

        foreach ($definition['fields'] as $field => $type) {
            $rules[$field] = ['required', 'string', 'max:'.($type === 'text' ? 10000 : 2048)];
        }
        if ($definition['image']) {
            $rules['image'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:51200'];
        }

        return $rules;
    }
}
