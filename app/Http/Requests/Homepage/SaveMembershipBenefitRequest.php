<?php

namespace App\Http\Requests\Homepage;

class SaveMembershipBenefitRequest extends HomepageRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['label' => ['required', 'string', 'max:255'], 'icon' => ['required', 'in:diamond,gift,shopping-bag,tags,crown,sparkles,heart,star,calendar-heart,utensils,flower-2,ticket-percent,shield-check,gem,coffee,waves'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['required', 'boolean']];
    }
}
