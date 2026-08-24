<?php

namespace App\Http\Requests\Concierge;

use Illuminate\Foundation\Http\FormRequest;

class KnowledgeItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:20000'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
