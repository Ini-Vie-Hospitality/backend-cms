<?php

namespace App\Http\Requests\Homepage;

class SaveMembershipBenefitRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['label' => ['required', 'string', 'max:255'], 'icon' => ['required', 'in:diamond,gift,shopping-bag,tags'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['required', 'boolean']];
    }
}
