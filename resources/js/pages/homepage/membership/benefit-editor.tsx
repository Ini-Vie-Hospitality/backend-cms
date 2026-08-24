import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

export type Benefit = {
    id: number;
    label: string;
    icon: string;
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
            className="grid gap-3 rounded-md border p-4 md:grid-cols-4"
        >
            <Input
                name="label"
                placeholder="Benefit label"
                defaultValue={benefit?.label}
            />
            <select
                name="icon"
                defaultValue={benefit?.icon ?? 'diamond'}
                className="h-10 rounded-md border bg-background"
            >
                <option value="diamond">Diamond</option>
                <option value="gift">Gift</option>
                <option value="shopping-bag">Shopping bag</option>
                <option value="tags">Tags</option>
            </select>
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
