<?php

namespace App\Http\Requests\Homepage;

class UpdateBrandIntroductionRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        $rules = ['title' => ['required', 'string', 'max:255'], 'quote' => ['required', 'string', 'max:10000'], 'status' => $this->publicationRules()];

        foreach ([1, 2] as $slot) {
            $rules["word_$slot"] = ['required', 'string', 'max:255'];
            $rules["paragraph_$slot"] = ['required', 'string', 'max:10000'];
        }
        foreach ([1, 2, 3] as $slot) {
            $rules["image_$slot"] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'];
            $rules["image_alt_$slot"] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
