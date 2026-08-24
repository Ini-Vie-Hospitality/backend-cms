<?php

namespace App\Http\Requests\Homepage;

use Illuminate\Foundation\Http\FormRequest;

abstract class HomepageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return list<string> */
    protected function publicationRules(): array
    {
        return ['required', 'in:draft,published'];
    }
}
