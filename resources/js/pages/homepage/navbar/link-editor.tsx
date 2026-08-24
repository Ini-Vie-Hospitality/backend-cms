import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export type LinkRow = {
    id: number;
    audience: string;
    label: string;
    href: string;
    sort_order: number;
    is_active: boolean;
};

export function NavbarLinkEditor({
    link,
    index,
}: {
    link?: LinkRow;
    index: number;
}) {
    function save(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        data.set('is_active', data.get('is_active') ? '1' : '0');

        if (link) {
            data.set('_method', 'PUT');
        }

        router.post(
            '/cms/homepage/navbar/links' + (link ? '/' + link.id : ''),
            data,
        );
    }

    return (
        <form
            onSubmit={save}
            className="grid gap-3 rounded-md border p-4 md:grid-cols-5"
        >
            <div>
                <Label>Audience</Label>
                <select
                    name="audience"
                    defaultValue={link?.audience ?? 'desktop'}
                    className="h-10 w-full rounded-md border bg-background px-2"
                >
                    <option value="desktop">Desktop</option>
                    <option value="mobile">Mobile sidebar</option>
                </select>
            </div>
            <div>
                <Label>Label</Label>
                <Input name="label" defaultValue={link?.label} />
            </div>
            <div>
                <Label>Redirect</Label>
                <Input name="href" defaultValue={link?.href} />
            </div>
            <div>
                <Label>Order</Label>
                <Input
                    name="sort_order"
                    type="number"
                    defaultValue={link?.sort_order ?? index}
                />
                <label className="mt-2 flex gap-2 text-sm">
                    <input
                        name="is_active"
                        type="checkbox"
                        defaultChecked={link?.is_active ?? true}
                    />{' '}
                    Active
                </label>
            </div>
            <div className="flex items-end gap-2">
                <Button>{link ? 'Save' : 'Add'}</Button>
                {link && (
                    <Button
                        type="button"
                        variant="destructive"
                        onClick={() =>
                            router.delete(
                                '/cms/homepage/navbar/links/' + link.id,
                            )
                        }
                    >
                        Delete
                    </Button>
                )}
            </div>
        </form>
    );
}
