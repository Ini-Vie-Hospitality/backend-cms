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
            className="grid gap-3 rounded-xl border bg-card p-4 shadow-[0_2px_8px_rgba(44,36,28,0.025)] md:grid-cols-4"
        >
            <Input
                name="label"
                placeholder="Benefit label"
                defaultValue={benefit?.label}
            />
            <select
                name="icon"
                defaultValue={benefit?.icon ?? 'diamond'}
                className="h-10 rounded-md border border-input bg-card outline-none focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/12"
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
