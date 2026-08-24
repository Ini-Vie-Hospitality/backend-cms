<?php

namespace App\Http\Requests\Homepage;

use App\Support\HomepageDefinitions;

class UpdateSectionRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        $definition = HomepageDefinitions::section((string) $this->route('section'));
        $rules = ['status' => $this->publicationRules()];

        foreach ($definition['fields'] as $field => $type) {
            $rules[$field] = ['required', 'string', 'max:'.($type === 'text' ? 10000 : 2048)];
        }
        foreach ($definition['media'] as $field => $type) {
            $rules[$field] = $type === 'video'
                ? ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:51200']
                : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'];
        }

        return $rules;
    }
}
