import { router } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
} from '@/components/ui/select';
import { membershipBenefitIcons } from './membership-benefit-icons';
import type { MembershipBenefitIconKey } from './membership-benefit-icons';

export type Benefit = {
    id: number;
    label: string;
    icon: MembershipBenefitIconKey;
    sort_order: number;
    is_active: boolean;
};

export function MembershipBenefitEditor({
    benefit,
    index,
}: {
    benefit?: Benefit;
    index: number;
}) {
    const [selectedIcon, setSelectedIcon] = useState<MembershipBenefitIconKey>(
        benefit?.icon ?? 'diamond',
    );
    const selectedOption =
        membershipBenefitIcons.find(({ value }) => value === selectedIcon) ??
        membershipBenefitIcons[0];
    const SelectedIcon = selectedOption.icon;

    function save(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        data.set('is_active', data.get('is_active') ? '1' : '0');

        if (benefit) {
            data.set('_method', 'PUT');
        }

        router.post(
            '/cms/homepage/membership/benefits' +
                (benefit ? '/' + benefit.id : ''),
            data,
        );
    }

    return (
        <form
            onSubmit={save}
            className="grid gap-3 rounded-xl border bg-card p-4 shadow-[0_2px_8px_rgba(44,36,28,0.025)] md:grid-cols-4"
        >
            <Input
                name="label"
                placeholder="Benefit label"
                defaultValue={benefit?.label}
            />
            <input type="hidden" name="icon" value={selectedIcon} />
            <Select
                value={selectedIcon}
                onValueChange={(value) =>
                    setSelectedIcon(value as MembershipBenefitIconKey)
                }
            >
                <SelectTrigger className="w-full">
                    <span className="flex items-center gap-2">
                        <SelectedIcon className="size-4" aria-hidden="true" />
                        {selectedOption.label}
                    </span>
                </SelectTrigger>
                <SelectContent>
                    {membershipBenefitIcons.map(
                        ({ value, label, icon: Icon }) => (
                            <SelectItem key={value} value={value}>
                                <Icon className="size-4" aria-hidden="true" />
                                <span>{label}</span>
                            </SelectItem>
                        ),
                    )}
                </SelectContent>
            </Select>
            <Input
                name="sort_order"
                type="number"
                defaultValue={benefit?.sort_order ?? index}
            />
            <div>
                <label className="mr-3">
                    <input
                        name="is_active"
                        type="checkbox"
                        defaultChecked={benefit?.is_active ?? true}
                    />{' '}
                    Active
                </label>
                <Button>{benefit ? 'Save' : 'Add'}</Button>
                {benefit && (
                    <button
                        type="button"
                        className="ml-2 text-destructive"
                        onClick={() =>
                            router.delete(
                                '/cms/homepage/membership/benefits/' +
                                    benefit.id,
                            )
                        }
                    >
                        Delete
                    </button>
                )}
            </div>
        </form>
    );
}
