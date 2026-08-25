<?php

namespace App\Http\Requests\Cms;

use App\Support\CmsCopilot;
use Illuminate\Foundation\Http\FormRequest;

class CopilotGenerateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $contexts = implode(',', array_keys(CmsCopilot::contexts()));
        $context = $this->input('context');

        return [
            'context' => ['required', 'string', 'in:'.$contexts],
            'target' => ['required', 'string', 'max:32'],
            'target_action' => ['required', 'string', 'max:2048'],
            'prompt' => ['required', 'string', 'min:2', 'max:4000'],
            'history' => ['nullable', 'array', 'max:10'],
            'history.*.role' => ['required_with:history', 'string', 'in:user,assistant'],
            'history.*.content' => ['required_with:history', 'string', 'max:8000'],
            'current_values' => ['nullable', 'array', 'max:30'],
            'current_values.*' => ['nullable', 'string', 'max:10000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $context = $this->input('context');
            $targetKey = (string) $this->input('target');
            $action = (string) $this->input('target_action');

            if (! is_string($context) || ! array_key_exists($context, CmsCopilot::contexts()) || ! array_key_exists($targetKey, CmsCopilot::contexts()[$context]['targets'])) {
                $validator->errors()->add('target', 'The selected form target is unavailable.');

                return;
            }

            if (! CmsCopilot::validateTargetAction(CmsCopilot::target($context, $targetKey), $action)) {
                $validator->errors()->add('target_action', 'Copilot cannot target this form.');
            }
        });
    }
}
